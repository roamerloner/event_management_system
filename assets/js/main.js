document.addEventListener('DOMContentLoaded', function(){
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e){
            if(!confirm('Are you sure you want to delete this item?')){
                e.preventDefault();
            }
        });
    });

   const forms = document.querySelectorAll('form');
   forms.forEach(form => {
    form.addEventListener('submit', function(e){
        if(!form.checkValidity()){
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
   });


});