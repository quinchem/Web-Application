document
.getElementById("forgotForm")

.addEventListener("submit", function(e){

    e.preventDefault();

    let formData = new FormData(this);

    fetch(

        "/Web-Application/Index.php?page=handle_forgot_password",

        {

            method: "POST",

            body: formData

        }

    )

    .then(response => response.json())

    .then(data => {

        if(data.status === "success"){

            Swal.fire({

                icon: "success",

                title: "Thành công",

                text: data.message,

                confirmButtonColor: "#d10016"

            });

        }else{

            Swal.fire({

                icon: "error",

                title: "Lỗi",

                text: data.message,

                confirmButtonColor: "#d10016"

            });

        }

    })

    .catch(error => {

        console.log(error);

    });

});