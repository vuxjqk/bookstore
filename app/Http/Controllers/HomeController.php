<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookAttributesService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $bookAttributes;

    public function __construct(BookAttributesService $bookAttributes)
    {
        $this->bookAttributes = $bookAttributes;
    }

    public function index()
    {
        return view('home.index');
    }

    public function show(Book $book)
    {
        $coverTypes = $this->bookAttributes->getCoverTypes();
        $book->load(['author', 'publisher', 'categories', 'images']);

        // Dữ liệu mẫu cho sản phẩm
        // $book = [
        //     'id' => 1,
        //     'title' => 'Harry Potter và Hòn đá Phù thủy',
        //     'author' => 'J.K. Rowling',
        //     'publisher' => 'NXB Trẻ',
        //     'price' => 299000,
        //     'original_price' => 350000,
        //     'discount' => 15,
        //     'rating' => 4.8,
        //     'review_count' => 2847,
        //     'in_stock' => true,
        //     'stock_quantity' => 25,
        //     'description' => 'Harry Potter và Hòn đá Phù thủy là cuốn sách đầu tiên trong series Harry Potter nổi tiếng của tác giả J.K. Rowling. Câu chuyện kể về cậu bé Harry Potter 11 tuổi khám phá ra mình là một phù thủy và bước vào thế giới phép thuật tại trường Hogwarts.',
        //     'detailed_description' => 'Đây là một tác phẩm kinh điển của văn học thiếu nhi, đã được dịch ra hơn 80 ngôn ngữ và bán hàng triệu bản trên toàn thế giới. Cuốn sách không chỉ thu hút trẻ em mà còn chinh phục cả người lớn với cốt truyện hấp dẫn, nhân vật sinh động và thế giới phép thuật đầy màu sắc.',
        //     'isbn' => '978-604-2-21234-5',
        //     'pages' => 320,
        //     'language' => 'Tiếng Việt',
        //     'publication_year' => 2023,
        //     'category' => 'Tiểu thuyết thiếu nhi',
        //     'images' => ['https://salt.tikicdn.com/cache/750x750/ts/product/5e/18/24/2a6154ba08df6ce6161c13f4303fa19e.jpg', 'https://salt.tikicdn.com/cache/750x750/ts/product/cd/e1/84/d75ba83c97a7f21c1b96b3fc1b36d2be.jpg', 'https://salt.tikicdn.com/cache/750x750/ts/product/6c/86/21/8e9b8c4b4ed8b3cbc3a2d3b4c5c6d7e8.jpg', 'https://salt.tikicdn.com/cache/750x750/ts/product/9d/f2/35/1f2e3d4c5b6a7f8e9d0c1b2a3f4e5d6c.jpg'],
        // ];

        $related_books = [
            [
                'id' => 2,
                'title' => 'Harry Potter và Phòng chứa Bí mật',
                'author' => 'J.K. Rowling',
                'price' => 320000,
                'image' => 'https://salt.tikicdn.com/cache/280x280/ts/product/8e/71/04/ed84c6b4c84c5e0e0c4d4f5e6f7e8f9e.jpg',
                'rating' => 4.9,
            ],
            [
                'id' => 3,
                'title' => 'Harry Potter và Tên tù nhân Azkaban',
                'author' => 'J.K. Rowling',
                'price' => 340000,
                'image' => 'https://salt.tikicdn.com/cache/280x280/ts/product/7f/82/15/fc95d7c5d95d6f1f1d5e5g6f7g8f9g0f.jpg',
                'rating' => 4.7,
            ],
            [
                'id' => 4,
                'title' => 'Chúa tể những chiếc nhẫn',
                'author' => 'J.R.R. Tolkien',
                'price' => 450000,
                'image' => 'https://salt.tikicdn.com/cache/280x280/ts/product/6e/93/26/eda6e8d6e86e7g2g2e6f6h7g8h9g0h1g.jpg',
                'rating' => 4.8,
            ],
            [
                'id' => 5,
                'title' => 'Narnia - Sư tử, Phù thủy và Tủ áo',
                'author' => 'C.S. Lewis',
                'price' => 280000,
                'image' => 'https://salt.tikicdn.com/cache/280x280/ts/product/5d/a4/37/feb7f9e7f97f8h3h3f7g7i8h9i0h1i2h.jpg',
                'rating' => 4.6,
            ],
        ];

        $reviews = [
            [
                'user' => 'Nguyễn Minh Anh',
                'rating' => 5,
                'date' => '2024-01-15',
                'comment' => 'Cuốn sách tuyệt vời! Con tôi rất thích đọc. Chất lượng in ấn đẹp, nội dung hấp dẫn.',
                'avatar' => 'https://ui-avatars.com/api/?name=Nguyen+Minh+Anh&background=3b82f6&color=fff',
            ],
            [
                'user' => 'Trần Văn Hùng',
                'rating' => 4,
                'date' => '2024-01-10',
                'comment' => 'Sách hay, dịch thuật tốt. Giá cả hợp lý. Giao hàng nhanh.',
                'avatar' => 'https://ui-avatars.com/api/?name=Tran+Van+Hung&background=ef4444&color=fff',
            ],
            [
                'user' => 'Lê Thị Mai',
                'rating' => 5,
                'date' => '2024-01-05',
                'comment' => 'Một tác phẩm kinh điển không thể bỏ qua. Đọc xong muốn mua tiếp các tập khác.',
                'avatar' => 'https://ui-avatars.com/api/?name=Le+Thi+Mai&background=10b981&color=fff',
            ],
        ];

        return view('home.show', compact('book', 'coverTypes', 'related_books', 'reviews'));
    }
}
