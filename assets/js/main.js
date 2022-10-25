function toggleCompleted(element, id) {
    let save_onclick = element.onclick;
    element.onclick = '';
    var xhr = new XMLHttpRequest();
        var body = 'id=' + encodeURIComponent(id);
        xhr.open('POST', location.origin + '/main/completed', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            element.classList.toggle("btn-outline-secondary");
            element.classList.toggle("btn-success");
            element.onclick = save_onclick;
        }
        };
        xhr.send(body);
}

function filterByCompleted(completed) {
    checkActived(completed);
    let all = document.getElementById('all');
    let incompl = document.getElementById('incomplete');
    let compl = document.getElementById('completed');
    let save_all_onclick = all.onclick;
    let save_incompl_onclick = incompl.onclick;
    let save_compl_onclick = compl.onclick;
    all.onclick = incompl.onclick = compl.onclick = '';
    var xhr = new XMLHttpRequest();
        var body = '';
        xhr.open('POST', location.origin + '/list_controller/status/'+ completed, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = xhr.responseText;
            var goals = JSON.parse(json);
            if (goals) {
                //updateAmountInFilters(goals, all, incompl, compl);
                //updateListOfGoals(goals, completed);
                console.log(goals);
                all.onclick = save_all_onclick;
                incompl.onclick = save_incompl_onclick;
                compl.onclick = save_compl_onclick;
            }
        }
        };
        xhr.send(body);
}

function updateListOfGoals(goals, completed) {
    let str = '';
    goals.forEach(function(goal, index, arr) {
        if(Number(goal['completed']) != completed && completed != 'all') return;
        str += `<tr style='cursor: pointer;' onclick="window.location='/main/goal/` + goal['id'] + `';" class='`+ (Number(goal['completed']) ? 'text-muted' : '') +`'>` +
                "<td><i style='margin-right:10px;' class='fa fa-check " + getCheckClassCompleted(goal['completed']) + " '></i>" + goal['title'] + "</td>" +
                "<td>" + showDate(goal['due_date']) + "</td>" +
                "<td><i class='" + getArrowClassPriority(goal['priority']) + "'></i>"+ " " + getStringPriority(goal['priority']) + " </td>" +
              "</tr>";
    });
    let showGoals = document.getElementById('showGoals');
    showGoals.innerHTML = str;
}

function updateAmountInFilters(goals, all, incompl, compl) {
    let completed = 0;
    let incomplete = 0;
    goals.forEach(function(goal, index,arr) {
        if(Number(goal['completed'])) {
            completed++;
        } else {
            incomplete++;
        }
    });
    all.innerHTML = "All "+ goals.length;
    incompl.innerHTML = "Incomplete "+ incomplete;
    compl.innerHTML = "Complete "+ completed;
}

function showDate(date) {
    let [year, month, day] = date.split("-");
    return [day, month, year].join('.');
}

function getCheckClassCompleted(completed) {
    switch(completed) {
        case '0':
            return 'text-muted';
            break;
        case '1':
            return 'text-success';
            break;
    }
    return false;
}

function getStringPriority(priority_number) {
    switch(priority_number) {
        case '1':
            return 'High';
            break;
        case '2':
            return 'Medium';
            break;
        case '3':
            return 'Low';
            break;
    }
    return false;
    
}

function getArrowClassPriority(priority_number) {
    switch(priority_number) {
        case '1':
            return 'fa fa-arrow-up text-danger';
            break;
        case '2':
            return 'fa fa-arrow-up text-warning';
            break;
        case '3':
            return 'fa fa-arrow-down text-primary';
            break;
    }
    return false;
    
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
        case true:
            compl.classList.add('text-primary');
            compl.classList.remove('text-muted');
            incompl.classList.remove('text-primary');
            incompl.classList.add('text-muted');
            all.classList.remove('text-primary');
            all.classList.add('text-muted');
            break;
        case false:
            incompl.classList.add('text-primary');
            incompl.classList.remove('text-muted');
            all.classList.remove('text-primary');
            all.classList.add('text-muted');
            compl.classList.remove('text-primary');
            compl.classList.add('text-muted');
            break;    
    }
}

function validateForm() {
    let form =  document.forms["goalForm"];
    let title = form["title"];
    let description = form["description"];
    let due_date = form["due_date"];
    title.classList.remove('is-invalid');
    description.classList.remove('is-invalid');
    due_date.classList.remove('is-invalid');
    let regex_title = /^[a-zA-Z0-9!#$%^&*\'\/{}|?~+=\-_.` ]+$/;
    let regex_description = /^[a-zA-Z0-9!#$%^&*\'\/{}|?~+=\-_.` ]+$/;
    if( !((title.value.length < 80) && (title.value.length > 0) && (regex_title.test(title.value))) ) {
        title.classList.add('is-invalid');
        return false;
    }

    let description_stripped = description.value.replace(/\s{2,}/g,' ').replace(/[\t\n]/g,' ');
    if( !((description_stripped.length < 500) && (description.value.length > 0) && (regex_description.test(description_stripped))) ) {
        description.classList.add('is-invalid');
        return false;
    }

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0');
    let yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;
    let d1 = Date.parse(today);
    let d2 = Date.parse(due_date.value);
    let parts_date = due_date.value.split('-');
    let year = parts_date[0];
    if (!(d2 >= d1 && Number(year) < 2101)) {
        due_date.classList.add('is-invalid');
        return false;
    }
    return true;
  }