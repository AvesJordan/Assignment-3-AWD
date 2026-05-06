document.addEventListener('DOMContentLoaded', () => {
    // Mobile sidebar toggle if needed in future, currently sidebar is fixed in desktop.
    console.log("Tunombili Farm App Initialized");

    // Dynamic data loading for modals could go here
    // Example: Populate edit modal with crop data
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Handle loading data into modal form
        });
    });
});
