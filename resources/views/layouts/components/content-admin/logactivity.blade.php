<style>
    .activity-scroll {
        max-height: 375px;
        overflow-y: auto;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-right: 120px;
        transition: background-color 0.2s ease-in-out;
    }

    .list-group-item:hover {
        background-color: #f9f9f9;
    }

    .list-group-item small {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
    }

    .card-title {
        font-weight: 600;
    }
</style>


<script>
    document.getElementById('sortOrder').addEventListener('change', function() {
        const sortOrder = this.value;
        const list = document.getElementById('activityList');
        const items = Array.from(list.querySelectorAll('.list-group-item'));

        items.sort((a, b) => {
            const timeA = new Date(a.getAttribute('data-time'));
            const timeB = new Date(b.getAttribute('data-time'));
            return sortOrder === 'asc' ? timeA - timeB : timeB - timeA;
        });

        // Clear and re-append in sorted order
        list.innerHTML = '';
        items.forEach(item => list.appendChild(item));
    });
</script>
