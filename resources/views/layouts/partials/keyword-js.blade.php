<script>
document.addEventListener('DOMContentLoaded', function() {
    const addKeywordBtn = document.getElementById('add-keyword');
    const keywordsContainer = document.getElementById('keywords-container');

    if (!addKeywordBtn || !keywordsContainer) return;

    addKeywordBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const html = `
            <div class="flex items-center space-x-2">
                <input type="text" name="keywords[]"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                       placeholder="Keyword...">
                <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-keyword">
                    ✕
                </button>
            </div>
        `;
        keywordsContainer.insertAdjacentHTML('beforeend', html);
        attachRemoveListeners();
    });

    function attachRemoveListeners() {
        document.querySelectorAll('.remove-keyword').forEach(btn => {
            btn.removeEventListener('click', removeKeyword);
            btn.addEventListener('click', removeKeyword);
        });
    }

    function removeKeyword(e) {
        e.preventDefault();
        this.parentElement.remove();
    }

    attachRemoveListeners();
});
</script>
