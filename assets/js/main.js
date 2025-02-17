document.addEventListener('DOMContentLoaded', function() {
    // إظهار/إخفاء نموذج الرسائل
    const messageButtons = document.querySelectorAll('.show-message-form');
    messageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.getAttribute('data-form');
            const messageForm = document.getElementById(formId);
            messageForm.style.display = messageForm.style.display === 'none' ? 'block' : 'none';
        });
    });

    // تأكيد الحذف
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                e.preventDefault();
            }
        });
    });
});
