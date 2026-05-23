document
.getElementById("forgotForm")

.addEventListener("submit", function(e){

    e.preventDefault();

    let formData = new FormData(this);

    fetch(

        "http://localhost/Web-Application/Admin_index.php?url=admin/forgot-password",

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

            }).then(() => {

                // CHUYỂN SANG RESET PASSWORD

                window.location.href =

                "http://localhost/Web-Application/Admin_index.php?url=admin/reset-password";

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