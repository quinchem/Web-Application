$(document).ready(function () {

    // TOGGLE PASSWORD

    $('.toggle-password').click(function () {

        let input = $(this).siblings('input');

        if (input.attr('type') === 'password') {

            input.attr('type', 'text');

            $(this)
                .removeClass('fa-eye-slash')
                .addClass('fa-eye');

        } else {

            input.attr('type', 'password');

            $(this)
                .removeClass('fa-eye')
                .addClass('fa-eye-slash');
        }
    });


    // PASSWORD STRENGTH

    $('#password').on('keyup', function () {

        let password = $(this).val();

        let strength = 0;

        if (password.length >= 6) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^A-Za-z0-9]/)) strength++;

        let width = 0;
        let color = '';
        let text = '';

        switch (strength) {

            case 1:
                width = 25;
                color = '#dc2626';
                text = 'ĐỘ BẢO MẬT: YẾU';
                break;

            case 2:
                width = 50;
                color = '#f59e0b';
                text = 'ĐỘ BẢO MẬT: TRUNG BÌNH';
                break;

            case 3:
                width = 75;
                color = '#2563eb';
                text = 'ĐỘ BẢO MẬT: KHÁ';
                break;

            case 4:
                width = 100;
                color = '#16a34a';
                text = 'ĐỘ BẢO MẬT: MẠNH';
                break;

            default:
                width = 0;
                color = '#d1d5db';
                text = 'ĐỘ BẢO MẬT: CHƯA CÓ';
        }

        $('#strength-fill').css({
            width: width + '%',
            background: color
        });

        $('#strength-text').text(text);
    });


    // AJAX SUBMIT

    $('#resetPasswordForm').submit(function (e) {

        e.preventDefault();

        $.ajax({

            url: 'http://localhost/Web-Application/Admin_index.php?page=admin/reset-password-ajax',

            type: 'POST',

            data: $(this).serialize(),

            success: function (response) {

                let data = JSON.parse(response);

                if (data.status) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message
                    }).then(() => {

                        window.location.href =
                            'Index.php?page=admin_dashboard#';
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message
                    });
                }
            }
        });
    });

});