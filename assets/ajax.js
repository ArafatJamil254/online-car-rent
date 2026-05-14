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