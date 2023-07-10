
window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            document.body.classList.toggle('sb-sidenav-toggled');
        }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

//this to handle form validation before submission (before sending request) check "onclick" property in each button
function submitForm(event,message,action){
    var button = event.target; // Get the clicked button element

  event.preventDefault();
  
  Swal.fire({
  title: message,

  showCancelButton: true,
  confirmButtonText: action,
  cancelButtonText: 'Annuler',

  }).then((result) => {
      if (result.isConfirmed) {
        var form = button.closest('form'); // Find the first parent form element
        form.submit();
      } 
  })
  }