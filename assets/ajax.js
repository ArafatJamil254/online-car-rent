
//Task2-23-54353-3(car validation and delete member ajax)
function carValidation(){
    let name = document.getElementById('name').value;
    let model = document.getElementById('model').value;
    let price = document.getElementById('price_per_day').value;
    let desc = document.getElementById('description').value;
    if(name=='' || model=='' || price=='' || desc==''){
        alert('All fields are required');
        return false;
    }
    if(price <= 0){
        alert('Price must be positive');
        return false;
    }
    return true;
}

function deleteMemberAjax(id){
    if(confirm('Are you sure to delete this member?')){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                let data = JSON.parse(this.responseText);
                alert(data.message);
                if(data.status == 'success'){
                    document.getElementById('memberRow'+id).remove();
                }
            }
        };
        xhttp.open('POST', '../controllers/deleteMember.php', true);
        xhttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
        xhttp.send('id='+id);
    }   
}