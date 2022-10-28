function toggleStatus(element, id, status) {
    var xhr = new XMLHttpRequest();
    var body = 'id=' + encodeURIComponent(id);
    status = Number(status) == 1 ? 0 : 1;
    xhr.open('PUT', location.origin + '/list_controller/toggle/' + id + '/' + status, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            element.classList.toggle("btn-secondary");
            element.classList.toggle("btn-primary");
            filterBy();
        }
    };
    xhr.send(body);
}

function deletePurchase(id) {
    var xhr = new XMLHttpRequest();
    var body = 'id=' + encodeURIComponent(id);
    xhr.open('DELETE', location.origin + '/list_controller/delete_purchase/' + id, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            filterBy();
        }
    };
    xhr.send(body);
}

function filterBy() {
    let filter = document.getElementById("select_filter").value;
    let status = '';
    let all = document.getElementById('all');
    let incompl = document.getElementById('incomplete');
    let compl = document.getElementById('completed');
    status = all.classList.contains('text-primary') ? 'all' : status;
    status = incompl.classList.contains('text-primary') ? 0 : status;
    status = compl.classList.contains('text-primary') ? 1 : status;
    var xhr = new XMLHttpRequest();
    var body = '';
    xhr.open('GET', location.origin + '/list_controller/status/' + status + '/' + filter, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = xhr.responseText;
            var goals = JSON.parse(json);
            if (goals) {
                updateListOfGoals(goals['purchases']);
            }
        }
    };
    xhr.send(body);
}

function updateListOfGoals(goals) {
    let str = '';
    goals.forEach(function(goal, index, arr) {
        str += `<tr class='w-50 `+ (Number(goal['status']) ? 'text-muted' : '') +`'>` +
                "<td>" + goal['name'] + "</td>" +
                "<td>" + goal['category'] + "</td>" +
                "<td>" + (Number(goal['status']) ? 'bought' : 'not bought') + "</td>" +
                "<td>" + showDate(goal['created']) + "</td>" +
                "<td style='text-align:center;'>" +
                    '<button type="button" class="btn ' + (Number(goal['status']) ? "btn-primary" : "btn-secondary") + '" style="cursor: pointer;" onclick="toggleStatus(this, ' + goal['id'] + ', ' + goal['status'] + ')">' +
                        '<i class="fa fa-check"></i>' +
                    '</button>' +
                '</td>' +
                "<td style='text-align:center;'>" +
                    '<button type="button" class="btn btn-danger" style="cursor: pointer;" onclick="deletePurchase(' + goal['id'] + ')">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                "</td>" +
              "</tr>";
    });
    let showGoals = document.getElementById('showGoals');
    showGoals.innerHTML = str;
}

function showDate(date_string) {
    let [date, time] = date_string.split(" "); 
    let [year, month, day] = date.split("-");
    let [hour, minute, second] = time.split(":");
    let result = [day, month, year].join('.');
    result += " " + [hour, minute].join(':');
    return result;
}

function checkActived(completed) {
    let all = document.getElementById('all');
    let incompl = document.getElementById('incomplete');
    let compl = document.getElementById('completed');
    switch(completed) {
        case 'all':
            all.classList.add('text-primary');
            all.classList.remove('text-muted');
            incompl.classList.remove('text-primary');
            incompl.classList.add('text-muted');
            compl.classList.remove('text-primary');
            compl.classList.add('text-muted');
            break;
        case 1:
            compl.classList.add('text-primary');
            compl.classList.remove('text-muted');
            incompl.classList.remove('text-primary');
            incompl.classList.add('text-muted');
            all.classList.remove('text-primary');
            all.classList.add('text-muted');
            break;
        case 0:
            incompl.classList.add('text-primary');
            incompl.classList.remove('text-muted');
            all.classList.remove('text-primary');
            all.classList.add('text-muted');
            compl.classList.remove('text-primary');
            compl.classList.add('text-muted');
            break;    
    }
    filterBy();
}

function createPurchase() {
    let form =  document.forms["createPurchaseForm"];
    let name_field = form["name"];
    let category_field = form["category_select"];
    if(!category_field.value) {
        $('#purchase_category_error').html('you need to create at least one category');
        $('#category_select').addClass('is-invalid');
        return false;
    }
    var starttime = new Date();
    var isotime = new Date((new Date(starttime)).toISOString() );
    var fixedtime = new Date(isotime.getTime()-(starttime.getTimezoneOffset()*60000));
    var formatedMysqlString = fixedtime.toISOString().slice(0, 19).replace('T', ' ');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', location.origin + '/list_controller/create_purchase', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            filterBy();
            $('#addPurchase').modal('hide');
            name_field.value = '';
        } else if(xhr.readyState === 4 && xhr.status === 400) {
            var json = xhr.responseText;
            var respond = JSON.parse(json);
            $('#purchase_name_error').html(respond['message']);
            $('#purchase_name').addClass('is-invalid');
        }
    };
    xhr.send(JSON.stringify({name : name_field.value, category : category_field.value, date : formatedMysqlString}));
    return false;
}

function createCategory() {
    let form =  document.forms["createCategoryForm"];
    let name_field = form["category_name"];
    var xhr = new XMLHttpRequest();
    xhr.open('POST', location.origin + '/list_controller/create_category', true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = xhr.responseText;
            var respond = JSON.parse(json);
            $("#select_filter").append("<option value='"+respond['id']+"'>"+respond['name']+"</option>");
            $("#category_select").append("<option value='"+respond['id']+"'>"+respond['name']+"</option>");
            $('#addCategory').modal('hide');
            name.value = '';
        } else if(xhr.readyState === 4 && xhr.status === 400) {
            var json = xhr.responseText;
            var respond = JSON.parse(json);
            $('#category_error').html(respond['message']);
            $('#category_name').addClass('is-invalid');
        }
    };
    xhr.send(JSON.stringify({name : name_field.value}));
    return false;
}

function clearCategoryModal() {
    $('#category_error').html('');
    $('#category_name').removeClass('is-invalid');
    $('#category_name').val('');
}

function clearPurchaseModal() {
    $('#purchase_name_error').html('');
    $('#purchase_name').removeClass('is-invalid');
    $('#purchase_name').val('');
    $('#purchase_category_error').html('');
    $('#category_select').removeClass('is-invalid');
}