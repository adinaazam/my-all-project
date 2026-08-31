/**
 * ============================================
 * CONTACT MANAGER - JAVASCRIPT
 * ============================================
 */

/**
 * Fetch data via AJAX and update page
 * @param {string} params - URL parameters for the request
 */
async function fetchData(params) {
    try {
        const response = await fetch('contact.php?ajax=1&' + params);
        const data = await response.json();
        
        // Update contact list
        document.getElementById('contactList').innerHTML = data.list;
        
        // Update statistics
        document.getElementById('statistics').innerHTML = data.stats;
        
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

/**
 * Search contacts by name
 */
function doSearch() {
    const query = document.getElementById('searchInput').value;
    if (query.trim() === '') {
        viewAll();
        return;
    }
    fetchData('action=search&q=' + encodeURIComponent(query));
}

/**
 * View all contacts (reset search)
 */
function viewAll() {
    document.getElementById('searchInput').value = '';
    fetchData('action=view');
}

/**
 * Sort contacts
 * @param {string} order - 'asc' or 'desc'
 */
function doSort(order) {
    fetchData('action=sort&by=' + order);
}

/**
 * Clear all contacts (AJAX version)
 */
function clearAll() {
    if (!confirm('Delete all contacts?')) {
        return;
    }
    fetchData('action=clear');
}

/**
 * Handle Enter key on search input
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                doSearch();
            }
        });
    }
    
    // Override clear link to use AJAX
    const clearLink = document.querySelector('a[href="contact.php?clear=1"]');
    if (clearLink) {
        clearLink.addEventListener('click', function(e) {
            e.preventDefault();
            clearAll();
        });
    }
});

/**
 * Auto-focus search input on page load
 */
window.addEventListener('load', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.focus();
    }
});

/**
 * Handle form submissions for delete via AJAX
 * (Optional - currently uses traditional POST)
 */
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Traditional form submit already handled with confirm
            // This is just to ensure confirm works
            if (!confirm('Delete this contact?')) {
                e.preventDefault();
            }
        });
    });
});