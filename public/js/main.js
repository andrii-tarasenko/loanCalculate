function main() {
    let idClient = $('#idClient').val();
    let dateRegEx = /^\d{4}-\d{2}-\d{2}$/;
    let dateBirthday = $('#dateBirthday').val();
    let requestLimit = $('#requestLimit').val();
    let phone = $('#phone').val();

    if (!dateRegEx.test(dateBirthday)) {
        $('#dateError').text('Невірний формат дати. Використовуйте формат YYYY-MM-DD.');
    } else {
        $('#dateError').text('');
    }
    if (idClient.trim() !== '' && dateBirthday.trim() !== '' && phone.trim() !== '' && requestLimit.trim() !== '') {
        let data = {};
        if ($('#mail').val() !== '') {
            let mail = $('#mail').val();
            data['mail'] = mail;
        }
        if ($('#address').val() !== '') {
            let address = $('#address').val();
            data['address'] = address;
        }
        if ($('#monthSalary').val() !== '') {
            let monthSalary = $('#monthSalary').val();
            data['monthSalary'] = monthSalary;
        }
        if ($('#currSalary').val() !== '') {
            let currSalary = $('#currSalary').val();
            data['currSalary'] = currSalary;
        }
        data['idClient'] = idClient;
        data['dateBirthday'] = dateBirthday;
        data['phone'] = phone;
        data['requestLimit'] = requestLimit;
        $.ajax({
            url: 'http://localhost/api/decision_client',
            type: "POST",
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
            success: function(response) {
                let res = JSON.parse(response);
                if (res.success == true) {
                    $('.container').css('display', 'none');
                    $('.alert-success').text(res.message).show();
                } else {
                    $('.alert-danger').text(res.message).show();
                }
            },
            error: function() {
                $('.alert-danger').text('I don\'t know what happened, don\'t worry, everything will be fine. Take care of yourself and Ukraine!').show();
            }
        });
    } else {
        if (idClient.trim() == '') {
            $('#dateErroridClient').css('display', 'block');
            $('#dateErroridClient').text('Ідентифікатор клієнта не може бути пустим');
        }
        if (dateBirthday.trim() == '') {
            $('#dateErrorBirthday').css('display', 'block');
            $('#dateErrorBirthday').text('Дата народження не може бути пустою');
        }
        if (phone.trim() == '') {
            $('#dateErrorPone').css('display', 'block');
            $('#dateErrorPone').text('Телефон клієнта не може бути пустим');
        }
        if (requestLimit.trim() == '') {
            $('#dateErrrequestLimit').css('display', 'block');
            $('#dateErrrequestLimit').text('Бажана сума кредитного ліміт');
        }
    }
}
