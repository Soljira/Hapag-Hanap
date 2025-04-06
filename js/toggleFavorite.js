document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('notification');
    if (!notification) {
        console.error('Notification element not found');
    }
});

async function toggleFavorite() {
    const btn = document.getElementById('favorite-btn');
    const notification = document.getElementById('notification');
    const recipeId = new URLSearchParams(window.location.search).get('id');

    try {
        const response = await fetch('toggle-favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `recipe_id=${recipeId}`
        });
        
        const rawResponse = await response.text();
        console.log("Raw response:", rawResponse);
        
        const result = JSON.parse(rawResponse);

        if (result.success) {
            btn.innerHTML = result.is_favorite ? '★' : '☆';
            btn.style.color = result.is_favorite ? '#ffcc00' : '#ccc';
            showNotification(result.message, true);
        } else {
            showNotification(result.message, false);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Failed to update favorite status. Please try again.', false);
    }
}

function showNotification(message, isSuccess) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.style.display = 'block';
    notification.style.background = isSuccess ? '#4CAF50' : '#f44336';
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}


function showNotification(message, isSuccess) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.style.display = 'block';
    notification.style.background = isSuccess ? '#4CAF50' : '#f44336';
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}