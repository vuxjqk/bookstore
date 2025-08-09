<?php

namespace App\Exports;

use App\Models\Book;
use App\Services\BookAttributesService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $bookAttributes = app(BookAttributesService::class);
        $coverTypes = $bookAttributes->getCoverTypes();
        $statuses = $bookAttributes->getStatuses();

        $books = Book::with(['categories', 'author', 'publisher'])->get();

        return $books->map(function ($book, $index) use ($coverTypes, $statuses) {
            return [
                __('No.') => $index + 1,
                __('Title') => $book->title,
                __('Slug') => $book->slug,
                __('Author') => $book->author->name ?? __('N/A'),
                __('Publisher') => $book->publisher->name ?? __('N/A'),
                __('ISBN Code') => $book->isbn ?? __('N/A'),
                __('Language') => $book->language ?? __('N/A'),
                __('Description') => $book->description ?? __('N/A'),
                __('Page Count') => $book->pages ?? __('N/A'),
                __('Dimensions') => $book->dimensions ?? __('N/A'),
                __('Weight') => $book->weight ?? __('N/A'),
                __('Publication Year') => $book->publication_year ?? __('N/A'),
                __('Cover Type') => $coverTypes[$book->cover_type] ?? __('N/A'),
                __('Original Price') => $book->original_price,
                __('Sale Price') => $book->sale_price,
                __('Stock Quantity') => $book->stock_quantity,
                __('Status') => $statuses[$book->status],
                __('Category') => $book->categories->isNotEmpty()
                    ? $book->categories->pluck('name')->implode(', ')
                    : __('N/A'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            __('No.'),
            __('Title'),
            __('Slug'),
            __('Author'),
            __('Publisher'),
            __('ISBN Code'),
            __('Language'),
            __('Description'),
            __('Page Count'),
            __('Dimensions'),
            __('Weight'),
            __('Publication Year'),
            __('Cover Type'),
            __('Original Price'),
            __('Sale Price'),
            __('Stock Quantity'),
            __('Status'),
            __('Category'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
