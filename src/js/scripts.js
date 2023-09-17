let URL = './API/connAPI.php';
let URLI = '../../API/connAPI.php';
let user = localStorage.getItem('user');
let classUser = localStorage.getItem('pseudoClassUser');
let item = localStorage.getItem('item');

//MODAL FUNÇÕES
function toggleModal(size = false) {
    let modal = $('.fp-modal');

    resizeModal(size);

    let statusModal = true;
    if (modal.is(':visible')) {
        statusModal = false;

        $('.c-modal').html('');
    }

    modal.attr('show', statusModal);
    $('.b-modal').scrollTop(0);

    $('.x-modal').click(function () {
        toggleModal();
    });
}

function resizeModal(size = false) {
    $('.b-modal').removeClass('modal-small modal-large');

    let classes = {
        'small': 'modal-small',
        'large': 'modal-large',
    };

    $('.b-modal').addClass((classes[size]) ? classes[size] : '');
}

//Loader 
function toggleLoader(action) {
    if (action == 'show') $('.x-loader').fadeIn(100);

    if (action == 'hide') $('.x-loader').fadeOut(100);
}

//MSG error
function showError(msg = false) {
    $('.alert-box-error').show();

    if (msg) {
        $('.msg').text(msg);
    }

    $('.loader').fadeOut();
}

function closeAlertBox() {
    $('.alert-box').hide();
    $('.alert-box .msg').text('');
    $('.btn-no-link').show();
    $('.btn-link').hide().attr('href', ' ');
}

function showSuccess(msg = false, url = false) {
    $('.alert-box-success').show();

    if (msg) {
        $('.alert-box .msg').text(msg);
    }

    if (url) {
        $('.btn-no-link').hide();
        $('.btn-link').show().attr('href', url);
    }
}

//Filter column
function toggleTableOrder(column, type) {
    let element = $(`span.order[data-column-order="${column}"]`);
    let order = element.attr('data-table-order');

    let infos = [];
    let table = column.split('-')[1];
    column = column.split('-')[0];

    $(`table.${table} tbody tr`).each(function (index, element) {
        let temp = {
            'content': $(element).find(`td[data-column="${column}"]`).text(),
            'order': parseInt($(element).attr('data-table-order')),
            'element': element
        };

        infos.push(temp);
    });

    $(`span.order i.fa-sort-up, span.order i.fa-sort-down`).removeClass('actived');

    let newOrder;

    switch (order) {
        case 'false':
            newOrder = 'Asc';
            $(`span.order[data-column-order="${column}-${table}"] i.fa-sort-up`).addClass('actived');
            break;
        case 'Asc':
            newOrder = 'Desc';
            $(`span.order[data-column-order="${column}-${table}"] i.fa-sort-down`).addClass('actived');
            break;
        case 'Desc':
            newOrder = 'false';
            break;
    }

    element.attr('data-table-order', newOrder)

    if (type == 'money') {
        for (let prop in infos) {
            let item = infos[prop];

            let money = item.content;

            money = money.replace(' ', ' ');
            let moneySplit = money.split(' ');

            money = moneySplit[1].replace(/[.]/g, '').replace(',', '.');

            if (moneySplit[0].substr(0, 1) == '-') {
                money = `-${money}`;
            }

            infos[prop]['content'] = parseFloat(money);
        }
    } else if (type == 'date') {
        for (let prop in infos) {
            let item = infos[prop];

            let datetime = item.content;
            let splitDate = datetime.split(' ');

            let date = splitDate[0].split('/').reverse().join('-');
            let hour = splitDate[1];

            let timestamp = new Date(date + ' ' + hour).getTime();

            infos[prop]['content'] = timestamp;
        }
    } else if (type == 'numeric') {
        for (let prop in infos) {
            let item = infos[prop];

            let numeric = item.content;

            infos[prop]['content'] = parseFloat(numeric);
        }
    }

    if (newOrder == 'Asc') {
        infos.sort(function (a, b) {
            return (a.content > b.content) ? 1 : ((b.content > a.content) ? -1 : 0);
        });
    } else if (newOrder == 'Desc') {
        infos.sort(function (a, b) {
            return (a.content < b.content) ? 1 : ((b.content < a.content) ? -1 : 0);
        });
    } else {
        infos.sort(function (a, b) {
            return (a.order > b.order) ? 1 : ((b.order > a.order) ? -1 : 0);
        });
    }

    $(`table.${table} tbody tr`).remove();

    for (let prop in infos) {
        let row = infos[prop];

        $(`table.${table} tbody`).append(row.element);
    }

}

function startCommentChange() {
    let timeoutComment, timerComment, element;
    $(".comment-item input[name='txtRequestComment']").focus(function () {
        element = this;
    }).blur(function () {
        clearInterval(timeoutComment);
    }).keyup(function () {
        clearInterval(timeoutComment);
        timerComment = 0;
        timeoutComment = setInterval(function () {
            timerComment++;

            if (timerComment == 3) {
                clearInterval(timeoutComment);
            }
        }, 1000)
    });
}

//Validate cpf, onlyLetters, numbers
jQuery.validator.addMethod("cpf", function (value, element) {
    value = jQuery.trim(value);

    value = value.replace('.', '');
    value = value.replace('.', '');
    cpf = value.replace('-', '');
    while (cpf.length < 11) cpf = "0" + cpf;
    var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
    var a = [];
    var b = new Number;
    var c = 11;
    for (i = 0; i < 11; i++) {
        a[i] = cpf.charAt(i);
        if (i < 9) b += (a[i] * --c);
    }
    if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11 - x }
    b = 0;
    c = 11;
    for (y = 0; y < 10; y++) b += (a[y] * c--);
    if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11 - x; }

    var retorno = true;
    if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;

    return this.optional(element) || retorno;

}, "Informe um CPF válido");

jQuery.validator.addMethod("onlyLetters", function (value, element) {
    let number = /([0-9])/;
    if (value.match(number)) {
        return false;
    } else {
        return true;
    }
}, "Informe um nome válido");

jQuery.validator.addMethod("onlyNumbers", function (value, element) {
    let number = /([a-zA-Z])/;
    if (value.match(number)) {
        return false;
    } else {
        return true;
    }
}, "Informe um valor válido");

//Functions pages
function registerUser() {
    $('.x-loader').fadeOut();

    $('#registerForm').validate({
        rules: {
            txtName: {
                required: true,
                onlyLetters: true
            },
            txtEmail: {
                required: true,
                email: true
            },
            txtPassword: {
                required: true,
            },
            txtCPF: {
                required: true,
                cpf: true
            },
        },
        messages: {
            txtName: {
                required: 'Informe um nome',
                onlyLetters: 'Informe um nome válido'
            },
            txtEmail: {
                required: 'Informe um email',
                email: 'Informe um email válido'
            },
            txtPassword: {
                required: 'Informe uma senha'
            },
            txtCPF: {
                required: 'Informe um CPF',
                cpf: 'Informe um CPF válido'
            },
        },
        submitHandler: function (form) {

            let content = {
                'name': $('#txtName').val(),
                'email': $('#txtEmail').val(),
                'password': $('#txtPassword').val(),
                'cpf': $('#txtCPF').val()
            };

            $.ajax({
                type: 'POST',
                url: URLI,
                data: {
                    content: JSON.stringify(content),
                    function: 'registerUser'
                },
                success: function (obj) {
                    if (obj.status == 'erro') {
                        $('.notification').html(obj.data);
                    } else {
                        showSuccess('Cadastrado com sucesso!', '../../index.php');
                    }
                },
                error: function () {
                    $('.notification').html('Página não encontrada');
                }
            });
        }
    });
}

function loginClient() {
    $('.x-loader').fadeOut();

    $('#formLogin').validate({
        rules: {
            txtEmail: {
                required: true
            },
            txtPassword: {
                required: true,
                // minlength: 8
            },
        },
        messages: {
            txtEmail: {
                required: 'Informe um email'
            },
            txtPassword: {
                required: 'Informe sua senha'
            },
        },
        submitHandler: function (form) {

            let content = {
                'email': $('#txtEmail').val(),
                'password': $('#txtPassword').val(),
            };

            $.ajax({
                type: 'POST',
                url: URL,
                data: {
                    content: JSON.stringify(content),
                    function: 'loginClient'
                },
                success: function (obj) {
                    if (obj.data[0] == true) {
                        $(".notification").empty();

                        let key = obj.data[1].id;
                        localStorage.setItem('user', key);
                        let pseudoClassUser = obj.data[1];
                        localStorage.setItem('pseudoClassUser', JSON.stringify(pseudoClassUser));

                        window.location.href = './src/pages/indexmaster.php'

                    } else if (obj.status == 'erro') {
                        $('.notification').html(obj.data);
                    }
                },
                error: function () {
                    $('.notification').html('Página não encontrada');
                }
            });
        }
    });
}

function logout() {
    localStorage.removeItem('user')
    localStorage.removeItem('pseudoClassUser')
    window.location.href = '../pages/logout.php'
}

function getUser() {
    $('.x-loader').fadeIn();

    let user = localStorage.getItem('user');

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(user),
            function: 'getUser',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                $('.name-user').append(obj.data.name);
                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    })
}

function getUsers(action = false) {
    $('.x-loader').fadeIn();

    if (action == 'previous' || action == 'next') {
        action == 'previous' ? page-- : page++;
    } else {
        page = 1
    };

    let content = {
        'page': page,
        'limit': 10,
    };

    //tabela usuarios, poderia ter montado a estrutura que esta no organizeUsers no else do ajaxx
    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(content),
            function: 'getUsers',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                $('.page-number').text(page);
                organizeUsers(obj.data);
                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function paginationUsers(action = false) {
    $('.x-loader').fadeIn();

    if (action == 'previous' || action == 'next') {
        action == 'previous' ? page-- : page++;
    } else {
        page = 1
    };

    let content = {
        'page': page,
        'limit': 10,
    };

    //tabela usuarios, poderia ter montado a estrutura que esta no organizeUsers no else do ajaxx
    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(content),
            function: 'getUsers',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                $('.page-number').text(page);
                organizeUsers(obj.data);
                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function organizeUsers(user) {
    $('table.users tbody').empty();
    let limit = 10;

    for (var prop in user) {
        let users = user[prop];
        let html = $('#templateUsers').html();

        html = html.replace('{{name}}', users.name);
        html = html.replace('{{email}}', users.email);
        html = html.replace('{{cpf}}', users.cpf);

        $('table.users tbody').append(html);
    }

    if (page > 1) {
        $('.previous').show();
    } else {
        $('.previous').hide();
    }

    if (user.length < limit) {
        $('.next').hide();
    } else {
        $('.next').show();
    }
}

function searchUser(action = false) {
    $('.loader').fadeIn();

    let search = $('input[name="txtSearch"]').val();
    $('.previous').hide();
    $('.next').hide();

    if (action == 'previousSearch' || action == 'nextSearch') {
        action == 'previousSearch' ? page-- : page++;
    } else {
        page = 1
    };

    let content = {
        'search': search,
        'page': page,
        'limit': 10,
    };

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(content),
            function: 'searchUser',
        },
        success: function (response) {
            let obj = response;

            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                let user = obj.data;

                page == 1 && user.length < 10 ? $('.page-number').hide() : $('.page-number').show();

                $('.page-number').text(page);
                organizeSearch(user);
                $('.loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function organizeSearch(user) {
    $('table.users tbody').empty();
    let limit = 10;

    for (var prop in user) {
        let users = user[prop];
        let html = $('#templateUsers').html();

        html = html.replace('{{name}}', users.name);
        html = html.replace('{{email}}', users.email);
        html = html.replace('{{cpf}}', users.cpf);

        $('table.users tbody').append(html);
    }

    if (page > 1) {
        $('.previousSearch').show();
    } else {
        $('.previousSearch').hide();
    }

    if (user.length < limit) {
        $('.nextSearch').hide();
    } else {
        $('.nextSearch').show();
    }
}

function loadChart() {
    $('.x-loader').fadeIn();

    const ctx = document.getElementById('line-chart');

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Cadastro'],
            datasets: [{
                label: 'Usuarios',
                data: [],
                borderColor: 'rgb(55, 130, 116)',
                backgroundColor: 'rgb(55, 380, 116)',
            },
            {
                label: 'Colaboradores',
                data: [],
                borderColor: 'rgb(0, 180, 216)',
                backgroundColor: 'rgb(0, 180, 216)',
            }
            ]
        },

    });

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(user),
            function: 'loadChart',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                let users = obj.data.users.users;
                let collaborators = obj.data.collaborators.collaborators;

                myChart.data.datasets[0].data.push(users);
                myChart.data.datasets[1].data.push(collaborators);

                myChart.update();
                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }

    })
}

function registrationCollaborators() {
    $('.x-loader').fadeIn();
    $('.inputSearch').hide();
    $('.btn-search').hide();

    var data = new Date();
    var dia = String(data.getDate()).padStart(2, '0');
    var mes = String(data.getMonth() + 1).padStart(2, '0');
    var ano = data.getFullYear();
    dataAtual = ano + '-' + mes + '-' + dia;

    $('#formRegistration').validate({
        rules: {
            txtName: {
                required: true,
                onlyLetters: true
            },
            txtEmail: {
                required: true,
                email: true
            },
            txtCPF: {
                required: true,
                cpf: true
            },
        },
        messages: {
            txtName: {
                required: 'Informe um nome',
                onlyLetters: 'Informe um nome válido'
            },
            txtEmail: {
                required: 'Informe um email',
                email: 'Informe um email válido'
            },
            txtCPF: {
                required: 'Informe um CPF',
                cpf: 'Informe um CPF válido'
            },
        },
        submitHandler: function (form) {

            let name = $('#txtName').val();
            let email = $('#txtEmail').val();
            let cpf = $('#txtCPF').val();
            let data = dataAtual;

            autheticationCollaborators(name, email, cpf, data);

        }
    });
}

function autheticationCollaborators(name, email, cpf, data) {
    $('.h-modal-title').html('Confimar colaborador');
    let html = $('#templateCollaborators').html();
    $('.c-modal').html(html);

    toggleModal();

    $('#form-collaborators').validate({
        rules: {
            txtPassword: {
                required: true
            },
        },
        messages: {
            txtPassword: {
                required: 'Informe sua senha atual'
            }
        },
        submitHandler: function (form) {

            let content = {
                'name': name,
                'email': email,
                'cpf': cpf,
                'data': data,
                'password': $('#txtPassword').val(),
                'user': user
            };

            $.ajax({
                type: 'POST',
                url: URLI,
                data: {
                    content: JSON.stringify(content),
                    function: 'registrationCollaborators'
                },
                success: function (obj) {
                    if (obj.status == 'erro') {
                        showError(obj.data);
                    } else {
                        showSuccess('Cadastrado com sucesso!', '../pages/indexmaster.php');
                        toggleModal();
                    }
                },
                error: function () {
                    showError('Erro interno');
                }
            });
        }
    });
}

function loadLaunch(action = false) {
    $('.x-loader').fadeIn();
    $('.inputSearch').hide();
    $('.btn-search').hide();
    localStorage.setItem('item', 'users');

    if (action == 'previous' || action == 'next') {
        action == 'previous' ? page-- : page++;
    } else {
        page = 1
    };

    let content = {
        'page': page,
        'limit': 10,
    };

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(content),
            function: 'getUsersTable',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                $('.page-number').show();
                $('.page-number').text(page);
                organizeLaunch(obj.data)
                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function organizeLaunch(user) {
    $('table.users tbody').empty();
    let limit = 10;

    for (var prop in user) {
        let users = user[prop];
        let html = $('#templateUsers').html();

        html = html.replace('{{order}}', users.name);
        html = html.replace(/{{iduser}}/g, users.id);
        html = html.replace(/{{name}}/g, users.name);
        html = html.replace(/{{email}}/g, users.email);
        html = html.replace(/{{cpf}}/g, users.cpf);

        $('table.users tbody').append(html);
    }

    if (page > 1) {
        $('.previous').show();
    } else {
        $('.previous').hide();
    }

    if (user.length < limit) {
        $('.next').hide();
    } else {
        $('.next').show();
    }
}

function filterSearch() {
    $('.loader').fadeIn();

    $('#tableForm').validate({
        rules: {
            txtNames: {
                required: true,
            },
        },
        messages: {
            txtNames: {
                required: 'Informe um nome',
            },
        },
        submitHandler: function (form) {

            let content = {
                'name': $('select[name="txtNames"]').val(),
                'item': localStorage.getItem('item'),
            };

            $.ajax({
                type: 'POST',
                url: URLI,
                data: {
                    content: JSON.stringify(content),
                    function: 'filterUser'
                },
                success: function (obj) {
                    if (obj.status == 'erro') {
                        showError(obj.data);
                    } else {
                        let user = obj.data;

                        let selectName = $('select[name="txtNames"]').val()

                        if (selectName == '' || 'Filtro por nome') {
                            $('.clean').text('Limpar');
                            $('.clean').prop("disabled", false);
                        }

                        if (selectName == 'Limpar') {
                            $('.clean').text('Filtro por nome');
                            window.location.href = '../pages/launch.php';
                        }

                        $('.page-number').hide();
                        $('.previousCollaborators').hide();
                        $('.nextCollaborators').hide();
                        $('.previous').hide();
                        $('.next').hide();
                        organizeFilter(user);
                        $('.loader').fadeOut();
                    }
                },
                error: function () {
                    showError('Erro interno');
                }
            });
        }
    });
}

function organizeFilter(user) {
    $('table.users tbody').empty();

    for (var prop in user) {
        let users = user[prop];
        let html = $('#templateUsers').html();

        html = html.replace('{{order}}', users.name);
        html = html.replace(/{{iduser}}/g, users.id);
        html = html.replace(/{{name}}/g, users.name);
        html = html.replace(/{{email}}/g, users.email);
        html = html.replace(/{{cpf}}/g, users.cpf);

        $('table.users tbody').append(html);
    }

}

function selectUsers() {
    $('.x-loader').fadeIn();
    
    $('select[name="txtNames"]').append('<option class="clean" disabled="disabled" selected>Filtro por nome</option>');

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(user),
            function: 'getSelectUsers',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                let user = obj.data.users;

                for (var prop in user) {
                    let users = user[prop];

                    $('select[name="txtNames"]').append('<option value="' + users.name + '">' + users.name + '</option>');
                }

                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });
}

function selectCollaborators() {
    $('.x-loader').fadeIn();

    $('select[name="txtNames"]').append('<option class="clean" disabled="disabled" selected>Filtro por nome</option>');

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(user),
            function: 'getSelectCollaborators',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                let collaborator = obj.data.collaborators;

                for (var prop in collaborator) {
                    let collaborators = collaborator[prop];

                    $('select[name="txtNames"]').append('<option value="' + collaborators.name + '">' + collaborators.name + '</option>');
                }

                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });
}

function updateUser(id, name, email, cpf) {
    $('.h-modal-title').html('Editando o ' + name);
    let html = $('#templateEdit').html();
    $('.c-modal').html(html);

    toggleModal();

    $('input[name="txtName"]').val(name);
    $('input[name="txtEmail"]').val(email);
    $('input[name="txtCPF"]').val(cpf);

    $('#editForm').validate({
        rules: {
            txtName: {
                required: true,
                onlyLetters: true
            },
            txtEmail: {
                required: true,
                email: true
            },
            txtPassword: {
                required: true,
            },
            txtCPF: {
                required: true,
                cpf: true
            },
        },
        messages: {
            txtName: {
                required: 'Informe um nome',
                onlyLetters: 'Informe um nome válido'
            },
            txtEmail: {
                required: 'Informe um email',
                email: 'Informe um email válido'
            },
            txtPassword: {
                required: 'Informe uma senha'
            },
            txtCPF: {
                required: 'Informe um CPF',
                cpf: 'Informe um CPF válido'
            },
        },
        submitHandler: function (form) {

            let content = {
                'name': $('#txtName').val(),
                'email': $('#txtEmail').val(),
                'cpf': $('#txtCPF').val(),
                'password': $('#txtPassword').val(),
                'item': localStorage.getItem('item'),
                'id': id,
                'user': user
            };

            $.ajax({
                type: 'POST',
                url: URLI,
                data: {
                    content: JSON.stringify(content),
                    function: 'editUser'
                },
                success: function (obj) {
                    if (obj.status == 'erro') {
                        showError(obj.data);
                    } else {
                        showSuccess('Editado com sucesso!', '../pages/launch.php');
                    }
                },
                error: function () {
                    $('.notification').html('Página não encontrada');
                }
            });
        }
    });
}

function deleteUser(id, name) {
    $('.h-modal-title').html('Excluir usuário');
    let html = $('#templateDelete').html();
    $('.c-modal').html(html);
    $('p').text('Deseja realmente excluir o ' + name + ' ?');

    toggleModal();

    $('#deleteForm').validate({
        rules: {
            txtPassword: {
                required: true
            },
        },
        messages: {
            txtPassword: {
                required: 'Informe sua senha atual'
            }
        },
        submitHandler: function (form) {

            let content = {
                'id': id,
                'password': $('#txtPassword').val(),
                'item': localStorage.getItem('item'),
                'user': user
            };

            $.ajax({
                type: 'POST',
                url: URLI,
                data: {
                    content: JSON.stringify(content),
                    function: 'deleteUser'
                },
                success: function (obj) {
                    if (obj.status == 'erro') {
                        showError(obj.data);
                    } else {
                        showSuccess('Excluído com sucesso!', '../pages/launch.php');
                        toggleModal();
                    }
                },
                error: function () {
                    showError('Erro interno');
                }
            });
        }
    });
}

function viewLaunch(item, action) {
    localStorage.setItem('item', item);

    if (action == 'previous' || action == 'next') {
        action == 'previous' ? page-- : page++;
    } else {
        page = 1
    };

    let limit = 10;

    let content = {
        'page': page,
        'limit': limit,
        'item': item
    };

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(content),
            function: 'ViewLaunch',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {

                if (item == 'users') {
                    $('.page-number').text(page);
                    $('.previousCollaborators').hide();
                    $('.nextCollaborators').hide();
                    $('.next').show();
                    $('.title-page-text').text('Relacionamento de usuários');
                    $('select[name="txtNames"]').empty();
                    selectUsers();
                    organizeViewLaunch(obj.data.users);
                } else if (item == 'collaborators') {
                    $('.page-number').text(page);
                    $('.previous').hide();
                    $('.next').hide();
                    $('.nextCollaborators').show();
                    $('.title-page-text').text('Relacionamento de colaboradores');
                    $('select[name="txtNames"]').empty();
                    selectCollaborators();
                    organizeViewLaunch(obj.data.collaborators);
                }

                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function organizeViewLaunch(user) {
    $('table.users tbody').empty();

    for (var prop in user) {
        let users = user[prop];
        let html = $('#templateUsers').html();

        html = html.replace('{{order}}', users.name);
        html = html.replace(/{{iduser}}/g, users.id);
        html = html.replace(/{{name}}/g, users.name);
        html = html.replace(/{{email}}/g, users.email);
        html = html.replace(/{{cpf}}/g, users.cpf);

        $('table.users tbody').append(html);
    }
}

function paginationCollaborators(action = false) {
    $('.x-loader').fadeIn();

    if (action == 'previousCollaborators' || action == 'nextCollaborators') {
        action == 'previousCollaborators' ? page-- : page++;
    } else {
        page = 1
    };

    let content = {
        'page': page,
        'limit': 10,
    };

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(content),
            function: 'getCollaborators',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                $('.page-number').text(page);
                organizeCollaborators(obj.data);
                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function organizeCollaborators(user) {
    $('table.users tbody').empty();
    let limit = 10;

    for (var prop in user) {
        let users = user[prop];
        let html = $('#templateUsers').html();

        html = html.replace('{{order}}', users.name);
        html = html.replace(/{{iduser}}/g, users.id);
        html = html.replace(/{{name}}/g, users.name);
        html = html.replace(/{{email}}/g, users.email);
        html = html.replace(/{{cpf}}/g, users.cpf);

        $('table.users tbody').append(html);
    }

    if (page > 1) {
        $('.previousCollaborators').show();
    } else {
        $('.previousCollaborators').hide();
    }

    if (user.length < limit) {
        $('.nextCollaborators').hide();
    } else {
        $('.nextCollaborators').show();
    }
}

function getData() {
    var date = new Date();
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var monthName =  date.toLocaleString('default', { month: 'long' });
    var year = date.getFullYear();
    var dateNow = day + '/' + month + '/' + year;

    var dataDay = new Date(year, month, 0);
    var dataEnd = dataDay.getDate() + '/' + month + '/' + year;
    var msg = $('.msg').text(dateNow + ' até ' + dataEnd);

    return {
        day: day,
        month: month,
        year: year,
        monthName: monthName,
        dateNow: dateNow,
        dataEnd: dataEnd,
        msg: msg
    };
}

function loadFinance() {
    $('.x-loader').fadeIn();
    $('.inputSearch').hide();
    $('.btn-search').hide();
    var data = getData();
    
    let content = {
        'month': data.month,
        'year' : data.year,
        'id'   : user
    };

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(content),
            function: 'getFinanceData',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                organizeBuys(obj.data);
                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function organizeBuys(buy) {
    $('table.product tbody').empty();
    var data = getData();
    data.msg;

    buy.getBuy.forEach(function(buys, index) {
        var html = $('#templateFinance').html();

        html = html.replace('{{order}}', buys.product);
        html = html.replace('{{product}}', buys.product);
        html = html.replace('{{place}}', buys.place);
        html = html.replace('{{value}}', buys.value);
        html = html.replace('{{date}}', buys.date);

        $('table.product tbody').append(html);
    });

    let totalValue = buy.getTotalValue[0].totalValue;
    $('#totalValue').text(totalValue);
}

function createProduct() {
    $('.h-modal-title').html('Cadastrar compra');
    let html = $('#templateCreate').html();
    $('.c-modal').html(html);

    toggleModal();
    var data = getData();

    $('#createForm').validate({
        rules: {
            txtProduct: {
                required: true,
            },
            txtValue: {
                required: true,
                onlyNumbers: true
            },
            txtPlace: {
                required: true,
            },
            txtPassword: {
                required: true,
            },
        },
        messages: {
            txtProduct: {
                required: 'Informe um nome',
            },
            txtValue: {
                required: 'Informe um o valor',
            },
            txtPlace: {
                required: 'Informe um lugar',
            },
            txtPassword: {
                required: 'Informe uma senha'
            },
        },
        submitHandler: function (form) {

            let content = {
                'product': $('#txtProduct').val(),
                'value': $('#txtValue').val(),
                'place': $('#txtPlace').val(),
                'password': $('#txtPassword').val(),
                'date': data.dateNow,
                'user': user
            };

            $.ajax({
                type: 'POST',
                url: URLI,
                data: {
                    content: JSON.stringify(content),
                    function: 'createProduct'
                },
                success: function (obj) {
                    if (obj.status == 'erro') {
                        showError(obj.data);
                    } else {
                        showSuccess('Cadastrado com sucesso!', '../pages/finance.php');
                    }
                },
                error: function () {
                    $('.notification').html('Página não encontrada');
                }
            });
        }
    });
}

function loadDates() {
    $('.x-loader').fadeIn();

    $('select[name="txtMonth"]').append('<option class="clean" disabled="disabled" selected>Filtro por mês</option>');

    $.ajax({
        type: 'POST',
        url: URLI,
        data: {
            content: JSON.stringify(user),
            function: 'getData',
        },
        success: function (obj) {
            if (obj.status == 'erro') {
                showError(obj.data);
            } else {
                let date = obj.data;

                for (var prop in date) {
                    let dates = date[prop];

                    $('select[name="txtMonth"]').append('<option value="' + dates.month + '">' + dates.month + ' | ' + dates.year + '</option>');
                }

                $('.x-loader').fadeOut();
            }
        },
        error: function () {
            showError('Erro interno');
        }
    });

}

function filterDate() {
    $('.loader').fadeIn();
    var data = getData();

    $('#tableMonth').validate({
        rules: {
            txtMonth: {
                required: true,
            },
        },
        messages: {
            txtMonth: {
                required: 'Informe um mês',
            },
        },
        submitHandler: function (form) {

            let content = {
                'month': $('select[name="txtMonth"]').val(),
                'year' : data.year,
                'id'   : user
            };

            $.ajax({
                type: 'POST',
                url: URLI,
                data: {
                    content: JSON.stringify(content),
                    function: 'getFinanceData'
                },
                success: function (obj) {
                    if (obj.status == 'erro') {
                        showError(obj.data);
                    } else {
                        let resultDate = obj.data;

                        let selectMonth = $('select[name="txtMonth"]').val()

                        if (selectMonth == '' || 'Filtro por mês') {
                            $('.clean').text('Limpar');
                            $('.clean').prop("disabled", false);
                        }

                        if (selectMonth == 'Limpar') {
                            $('.clean').text('Filtro por mês');
                            window.location.href = '../pages/finance.php';
                        }

                        organizeFilterDate(resultDate);
                        $('.loader').fadeOut();
                    }
                },
                error: function () {
                    showError('Erro interno');
                }
            });
        }
    });
}

function organizeFilterDate(resultDate) {
    $('table.product tbody').empty();
    var data = getData();
    data.msg;

    resultDate.getBuy.forEach(function(buys, index) {
        var html = $('#templateFinance').html();
    
        html = html.replace('{{order}}', buys.product);
        html = html.replace('{{product}}', buys.product);
        html = html.replace('{{place}}', buys.place);
        html = html.replace('{{value}}', buys.value);
        html = html.replace('{{date}}', buys.date);

        $('table.product tbody').append(html);
    });

    let totalValue = resultDate.getTotalValue[0].totalValue;
    $('#totalValue').text(totalValue);
}
