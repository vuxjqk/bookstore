<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Services\BookAttributesService;
use App\Services\BookUpdateService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    protected $bookAttributes;

    public function __construct(BookAttributesService $bookAttributes)
    {
        $this->bookAttributes = $bookAttributes;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $books = Book::with([
            'categories:id,name',
            'author:id,name',
            'publisher:id,name',
            'firstImage:id,book_id,image_path,alt_text'
        ])
            ->filter($request->all())
            ->paginate(10)
            ->appends($request->query());

        $categories = Category::all();
        $statuses = $this->bookAttributes->getStatuses();

        $totalBooks = Book::count();
        $totalAvailable = Book::where('stock_quantity', '>', 0)->count();
        $totalOutOfStock = Book::where('stock_quantity', '<=', 0)->count();
        $totalStock = Book::sum('stock_quantity');

        return view('books.index', compact(
            'books',
            'categories',
            'statuses',
            'totalBooks',
            'totalAvailable',
            'totalOutOfStock',
            'totalStock'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        $languages = $this->bookAttributes->getLanguages();
        $dimensions = $this->bookAttributes->getDimensions();
        $coverTypes = $this->bookAttributes->getCoverTypes();
        $statuses = $this->bookAttributes->getStatuses();

        return view('books.create', compact(
            'categories',
            'authors',
            'publishers',
            'languages',
            'dimensions',
            'coverTypes',
            'statuses'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = array_filter(
            $request->validate([
                'title' => 'required|string|max:255|unique:books,title',
                'slug' => 'required|string|max:255|unique:books,slug',
                'author_id' => 'nullable|exists:authors,id',
                'publisher_id' => 'nullable|exists:publishers,id',
                'isbn' => 'nullable|string|max:50|unique:books,isbn',
                'language' => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'pages' => 'nullable|integer|min:0',
                'dimensions' => 'nullable|string|max:50',
                'weight' => 'nullable|integer|min:0',
                'publication_year' => 'nullable|integer|between:1901,2155',
                'cover_type' => 'nullable|in:hardcover,paperback',
                'original_price' => 'nullable|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'nullable|integer|min:0',
                'status' => 'nullable|in:available,out_of_stock,pre_order,discontinued',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:4096',
            ]),
            fn($v) => !is_null($v)
        );

        try {
            $book = DB::transaction(function () use ($request, $validated) {
                $book = Book::create($validated);
                $book->categories()->sync($validated['categories'] ?? []);

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

                return $book;
            });

            return redirect()->route('books.show', $book)->with('success', __('Book created successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('An error occurred: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load([
            'author:id,name',
            'publisher:id,name',
            'categories:id,name',
            'images:id,book_id,image_path,alt_text'
        ]);

        $coverTypes = $this->bookAttributes->getCoverTypes();
        $statuses = $this->bookAttributes->getStatuses();

        return view('books.show', compact('book', 'coverTypes', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        $languages = $this->bookAttributes->getLanguages();
        $dimensions = $this->bookAttributes->getDimensions();
        $coverTypes = $this->bookAttributes->getCoverTypes();
        $statuses = $this->bookAttributes->getStatuses();

        return view('books.edit', compact(
            'book',
            'categories',
            'authors',
            'publishers',
            'languages',
            'dimensions',
            'coverTypes',
            'statuses'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $updateType = $request->input('update_type');
        $bookUpdateService = app(BookUpdateService::class);

        switch ($updateType) {
            case 'basic_info':
                $bookUpdateService->updateBasicInfo($request, $book);
                break;

            case 'technical':
                $bookUpdateService->updateTechnical($request, $book);
                break;

            case 'price_stock':
                $bookUpdateService->updatePriceStock($request, $book);
                break;

            case 'status':
                $bookUpdateService->updateStatus($request, $book);
                break;

            case 'categories':
                $bookUpdateService->updateCategories($request, $book);
                break;

            case 'images':
                $bookUpdateService->updateImages($request, $book);
                break;

            case 'all':
                $bookUpdateService->updateAll($request, $book);
                break;

            default:
                return redirect()->back()->with('error', __('Invalid update type.'));
                break;
        }

        return redirect()->back()->with('success', __('Book updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            $paths = $book->images->pluck('image_path')->toArray();

            DB::transaction(function () use ($book) {
                $book->categories()->detach();
                $book->images()->delete();
                $book->delete();
            });

            if ($paths) {
                Storage::disk('public')->delete($paths);
            }
            return redirect()->route('books.index')->with('success', __('Book deleted successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }

    public function export()
    {
        return Excel::download(new BooksExport, 'books.xlsx');
    }
}
