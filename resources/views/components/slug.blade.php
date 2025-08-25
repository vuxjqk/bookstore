@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Slug generation
            ['name', 'title'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', () => {
                        const slug = el.value
                            .toLowerCase()
                            .trim()
                            .replace(/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/g, 'a')
                            .replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/g, 'e')
                            .replace(/í|ì|ỉ|ĩ|ị/g, 'i')
                            .replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/g, 'o')
                            .replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/g, 'u')
                            .replace(/ý|ỳ|ỷ|ỹ|ỵ/g, 'y')
                            .replace(/đ/g, 'd')
                            .replace(/[^a-z0-9 -]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        document.getElementById('slug').value = slug;
                    });
                }
            });
        });
    </script>
@endPushOnce
