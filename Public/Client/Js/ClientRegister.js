// =====================================================
// AJAX REGISTER
// =====================================================

document
    .getElementById('registerForm')

    .addEventListener('submit', function (e) {

        e.preventDefault();

        const password =
            document.getElementById('password').value;

        const confirmPassword =
            document.getElementById('confirmPassword').value;

        // CHECK CONFIRM PASSWORD

        if (password !== confirmPassword) {

            Swal.fire({

                icon: 'error',

                title: 'Lỗi',

                text: 'Mật khẩu nhập lại không khớp',

                confirmButtonColor: '#c40016'
            });

            return;
        }

        const formData =
            new FormData(this);

        fetch(
            'index.php?page=register',
            {
                method: 'POST',
                body: formData
            }
        )

        .then(response => response.json())

        .then(data => {

            if (data.status === 'success') {

                Swal.fire({

                    icon: 'success',

                    title: 'Thành công',

                    text: data.message,

                    confirmButtonColor: '#c40016'

                }).then(() => {

                    window.location.href =
                        'index.php?page=login';
                });

            } else {

                Swal.fire({

                    icon: 'error',

                    title: 'Thất bại',

                    text: data.message,

                    confirmButtonColor: '#c40016'
                });
            }
        })

        .catch(error => {

            Swal.fire({

                icon: 'error',

                title: 'Lỗi hệ thống',

                text: 'Không thể xử lý yêu cầu',

                confirmButtonColor: '#c40016'
            });

            console.error(error);
        });

    });