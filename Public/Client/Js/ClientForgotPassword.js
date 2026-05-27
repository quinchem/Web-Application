document
    .getElementById('forgotPasswordForm')

    .addEventListener('submit', function (e) {

        e.preventDefault();

        const formData =
            new FormData(this);


        fetch(

            'index.php?page=forgot-password',

            {
                method: 'POST',

                body: formData
            }
        )

        .then(response => response.json())

        .then(data => {


            // =========================
            // SUCCESS
            // =========================

            if (data.status === 'success') {

                Swal.fire({

                    icon: 'success',

                    title: 'Thành công',

                    text: data.message,

                    confirmButtonColor: '#c40016'

                });

            }


            // =========================
            // ERROR
            // =========================

            else {

                Swal.fire({

                    icon: 'error',

                    title: 'Thất bại',

                    text: data.message,

                    confirmButtonColor: '#c40016'
                });
            }

        })


        // =========================
        // FETCH ERROR
        // =========================

        .catch(error => {

            console.error(error);

            Swal.fire({

                icon: 'error',

                title: 'Lỗi hệ thống',

                text: 'Không thể gửi yêu cầu',

                confirmButtonColor: '#c40016'
            });

        });

    });