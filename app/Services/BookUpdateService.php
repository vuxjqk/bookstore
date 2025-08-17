<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookUpdateService
{
    protected function rulesBasicInfo(Book $book)
    {
        return [
            'title' => 'required|string|max:255|unique:books,title,' . $book->id,
            'slug' => 'required|string|max:255|unique:books,slug,' . $book->id,
            'author_id' => 'nullable|exists:authors,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'isbn' => 'nullable|string|max:50|unique:books,isbn,' . $book->id,
            'language' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ];
    }

    protected function rulesTechnical()
    {
        return [
            'pages' => 'nullable|integer|min:0',
            'dimensions' => 'nullable|string|max:50',
            'weight' => 'nullable|integer|min:0',
            'publication_year' => 'nullable|integer|between:1901,2155',
            'cover_type' => 'nullable|in:hardcover,paperback',
        ];
    }

    protected function rulesPriceStock()
    {
        return [
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ];
    }

    protected function rulesStatus()
    {
        return [
            'status' => 'required|in:available,out_of_stock,pre_order,discontinued',
        ];
    }

    protected function rulesCategories()
    {
        return [
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ];
    }

    protected function rulesImages()
    {
        return [
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:4096',
            'existing_image_ids' => 'nullable|array',
            'existing_image_ids.*' => 'exists:book_images,id',
        ];
    }

    protected function validated(Request $request, array $rules)
    {
        return $request->validate($rules);
    }

    protected function handleImagesUpdate(Request $request, Book $book, array $validated)
    {
        if (isset($validated['existing_image_ids'])) {
            $book->images()->whereNotIn('id', $validated['existing_image_ids'])->each(function ($image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            });
        } else {
            Storage::disk('public')->delete($book->images->pluck('image_path')->toArray());
            $book->images()->delete();
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('book_images', 'public');
                $altText = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $book->images()->create([
                    'image_path' => $path,
                    'alt_text' => $altText,
                ]);
            }
        }
    }

    public function updateBasicInfo(Request $request, Book $book)
    {
        $validated = $this->validated($request, $this->rulesBasicInfo($book));
        $book->update($validated);
    }

    public function updateTechnical(Request $request, Book $book)
    {
        $validated = $this->validated($request, $this->rulesTechnical());
        $book->update($validated);
    }

    public function updatePriceStock(Request $request, Book $book)
    {
        $validated = $this->validated($request, $this->rulesPriceStock());
        $book->update($validated);
    }

    public function updateStatus(Request $request, Book $book)
    {
        $validated = $this->validated($request, $this->rulesStatus());
        $book->update($validated);
    }

    public function updateCategories(Request $request, Book $book)
    {
        $validated = $this->validated($request, $this->rulesCategories());
        $book->categories()->sync($validated['categories'] ?? []);
    }

    public function updateImages(Request $request, Book $book)
    {
        $validated = $this->validated($request, $this->rulesImages());
        $this->handleImagesUpdate($request, $book, $validated);
    }

    public function updateAll(Request $request, Book $book)
    {
        $rules = array_merge(
            $this->rulesBasicInfo($book),
            $this->rulesTechnical(),
            $this->rulesPriceStock(),
            $this->rulesStatus(),
            $this->rulesCategories(),
            $this->rulesImages()
        );
        $validated = $this->validated($request, $rules);

        DB::transaction(function () use ($request, $book, $validated) {
            $book->update($validated);
            $book->categories()->sync($validated['categories'] ?? []);
            $this->handleImagesUpdate($request, $book, $validated);
        });
    }
}
