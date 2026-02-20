function loadTable(url, target) {
    AjaxHelper.request({
        url: url,
        method: "GET",
        onSuccess: function (response) {
            // Try to find the target element in the response HTML
            const newContent = $(response).find(target).html();
            if (newContent) {
                $(target).html(newContent);
                // Re-initialize any plugins like Lucide icons
                if (window.lucide) {
                    lucide.createIcons();
                }
            } else {
                // If it's a full HTML response without specific target, or something else
                // $(target).html(response);
            }
        }
    });
}
