<?php

namespace App\Exports;

use App\Models\Book;
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
        $books = Book::with(['categories', 'author', 'publisher'])->get();

        return $books->map(function ($book, $index) {
            return [
                __('No.') => $index + 1,
                __('Title') => $book->title,
                __('Slug') => $book->slug,
                __('Author') => optional($book->author)->name,
                __('Publisher') => optional($book->publisher)->name,
                __('ISBN Code') => $book->isbn,
                __('Language') => $book->language,
                __('Description') => $book->description,
                __('Page Count') => $book->pages,
                __('Dimensions') => $book->dimensions,
                __('Weight') => $book->weight,
                __('Publication Year') => $book->publication_year,
                __('Cover Type') => $book->cover_type,
                __('Original Price') => $book->original_price,
                __('Sale Price') => $book->sale_price,
                __('Stock Quantity') => $book->stock_quantity,
                __('Status') => $book->status,
                __('Category') => $book->categories->pluck('name')->implode(', '),
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
