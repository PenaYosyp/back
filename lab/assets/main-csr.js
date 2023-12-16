const dataTable=document.getElementById('dataTable');
const pageHeader=document.getElementById('pageHeader');
const categoryForm=document.getElementById('categoryForm');
const propertyForm=document.getElementById('propertyForm');
const opticalDriveForm=document.getElementById('opticalDriveForm');
const opticalDriveLink=document.getElementById('opticalDriveLink');
const categoryLink=document.getElementById('categoryLink');
const propertyLink=document.getElementById('propertyLink');
const logoutLink=document.getElementById('logoutLink');
const categorySelect=document.getElementById('opticalDriveCategoryInput');
const contentContainer=document.getElementById('contentContainer');
const loginContainer=document.getElementById('loginContainer');
const loginForm=document.getElementById('loginForm');
const loginErrorText=document.getElementById('loginErrorText');
const propInputsContainer=document.getElementById('propInputsContainer');
function checkLogin(){
    fetch('http://localhost/lab/app/api/loginController.php')
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        console.log(data);
        if(data.userlogin=='') {
            loginContainer.style.display='flex';
        } else {
            getCategories();
            getProperties();
            getOpticalDrives();
            contentContainer.style.display='flex';
        }
    });
}
function getCategories() {
    fetch('http://localhost/lab/app/api/categoryController.php')
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        [].forEach.call(document.querySelectorAll('.app-form'), function (el) {
            el.style.display = 'none';
          });
        categoryForm.style.display='block';
        pageHeader.innerText='Категорії';
        let content=``;
        let selectContent=``;
        for(let i=0; i<data.length; i++) {
            selectContent+=`<option value="`+data[i].id+`">`+data[i].name+`</option>`
            content+=`<tr>
                        <td>`+data[i].id+`</td>
                        <td>`+data[i].name+`</td>
                        <td>
                            <a class='edit-category' data-id="`+data[i].id+`" href="#">Редагувати</a>
                            <a class='delete-category' data-id="`+data[i].id+`" href="#">Видалити</a>
                        </td>
            </tr>`;
        }
        dataTable.innerHTML=`<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+content+`
                            </tbody>`;
        categorySelect.innerHTML=selectContent;
    });
}
function getProperties() {
    fetch('http://localhost/lab/app/api/propertyController.php')
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        //console.log(data);
        [].forEach.call(document.querySelectorAll('.app-form'), function (el) {
            el.style.display = 'none';
          });
        propertyForm.style.display='block';
        pageHeader.innerText='Характеристики';
        let content=``;
        let inputContent=``
        for(let i=0;i<data.length;i++) {
            content+=`<tr>
                        <td>`+data[i].id+`</td>
                        <td>`+data[i].name+`</td>
                        <td>`+data[i].units+`</td>
                        <td>
                            <a class='edit-property' data-id="`+data[i].id+`" href="#">Редагувати</a>
                            <a class='delete-property' data-id="`+data[i].id+`" href="#">Видалити</a>
                        </td>
            </tr>`;
            inputContent+=`<p><input placeholder="`+data[i].name+`" data-id="`+data[i].id+`" class="prop-input" required/></p>`
        }
        dataTable.innerHTML=`<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Одиниці вимірювання</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+content+`
                            </tbody>`;
        propInputsContainer.innerHTML=inputContent;
    });
}
function getOpticalDrives() {
    fetch('http://localhost/lab/app/api/opticalDriveController.php')
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        [].forEach.call(document.querySelectorAll('.app-form'), function (el) {
            el.style.display = 'none';
          });
        opticalDriveForm.style.display='block';
        pageHeader.innerText='Оптичні приводи';
        let content=``;
        for(let i=0;i<data.length;i++) {
            let propertyContent=``;
            for(let j=0;j<data[i].properties.length;j++)
                propertyContent+=data[i].properties[j].name+`: `+data[i].properties[j].value+` `+data[i].properties[j].units+`</br>`;
            content+=`<tr>
                        <td>`+data[i].id+`</td>
                        <td>`+data[i].name+`</td>
                        <td>`+data[i].vendor+`</td>
                        <td>`+data[i].category+`</td>
                        <td>`+data[i].price+`</td>
                        <td>`+propertyContent+`</td>
                        <td>
                            <a class='edit-opticalDrive' data-id="`+data[i].id+`" href="#">Редагувати</a>
                            <a class='delete-opticalDrive' data-id="`+data[i].id+`" href="#">Видалити</a>
                        </td>
            </tr>`;
        }
        dataTable.innerHTML=`<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Виробник</th>
                                <th>Категорія</th>
                                <th>Ціна</th>
                                <th>Характеристики</th>
                                <th>Дії</th>
                            </thead>
                            <tbody>
                                `+content+`
                            </tbody>`;
    });
}
checkLogin();

categoryForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let categoryName=document.getElementById('categoryNameInput').value;
    let categoryId=document.getElementById('categoryIdInput').value;
    if(categoryId==''){
    let formData = new FormData();
    formData.append('name', categoryName);
    fetch("http://localhost/lab/app/api/categoryController.php",
        {
            body: formData,
            method: "POST"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            categoryForm.reset();
            getCategories();
        });
    } else {
        requestData={id:categoryId, name:categoryName};
        fetch("http://localhost/lab/app/api/categoryController.php",
        {
            body: JSON.stringify(requestData),
            method: "PUT"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            categoryForm.reset();
            getCategories();
            document.getElementById('categoryIdInput').value=''
        });
    }
  });
propertyForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let propertyName=document.getElementById('propertyNameInput').value;
    let propertyUnits=document.getElementById('propertyUnitsInput').value;
    let propertyId=document.getElementById('propertyIdInput').value;
    if(propertyId == '') {
        let formData = new FormData();
        formData.append('name', propertyName);
        formData.append('units', propertyUnits);
        fetch("http://localhost/lab/app/api/propertyController.php",
            {
                body: formData,
                method: "POST"
            }).then(()=>{
                propertyForm.reset();
                getProperties();
            });
    } else {
        requestData={id:propertyId, name:propertyName,units:propertyUnits};
        fetch("http://localhost/lab/app/api/propertyController.php",
        {
            body: JSON.stringify(requestData),
            method: "PUT"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            propertyForm.reset();
            getProperties();
            document.getElementById('propertyIdInput').value=''
        });
    }
  });
opticalDriveForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let opticalDriveName = document.getElementById('opticalDriveNameInput').value;
    let opticalDriveVendor = document.getElementById('opticalDriveVendorInput').value;
    let opticalDriveCategory = document.getElementById('opticalDriveCategoryInput').value;
    let opticalDrivePrice = document.getElementById('opticalDrivePriceInput').value;
    let opticalDriveId = document.getElementById('opticalDriveIdInput').value;
    if(opticalDriveId == '') {
        let formData = new FormData();
        formData.append('name', opticalDriveName);
        formData.append('vendor', opticalDriveVendor);
        formData.append('category', opticalDriveCategory);
        formData.append('price', opticalDrivePrice);
        var propInputs = document.getElementsByClassName("prop-input");
        for (var i=0; i<propInputs.length; i++)
            formData.append('prop_'+propInputs[i].getAttribute('data-id'), propInputs[i].value);
        fetch("http://localhost/lab/app/api/opticalDriveController.php",
            {
                body: formData,
                method: "POST"
            }).then(()=>{
                opticalDriveForm.reset();
                getOpticalDrives();
            });
    } else {
        requestData={id:opticalDriveId, name:opticalDriveName,vendor:opticalDriveVendor, price:opticalDrivePrice,category:opticalDriveCategory};
        var propInputs = document.getElementsByClassName("prop-input");
        for (var i=0; i<propInputs.length; i++)
            requestData['prop_'+propInputs[i].getAttribute('data-id')]= propInputs[i].value;
        fetch("http://localhost/lab/app/api/opticalDriveController.php",
        {
            body: JSON.stringify(requestData),
            method: "PUT"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            opticalDriveForm.reset();
            getOpticalDrives();
            document.getElementById('opticalDriveIdInput').value=''
        });
    }
  });
loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let login = document.getElementById('loginInput').value;
    let password = document.getElementById('passwordInput').value;
    let formData = new FormData();
    formData.append('login', login);
    formData.append('password', password);
    fetch("http://localhost/lab/app/api/loginController.php",
        {
            body: formData,
            method: "POST"
        }).then((response) => {
            return response.json();
        })
        .then((data) => {
            loginForm.reset();
            if(data.error == '') {
                loginContainer.style.display='none';
                contentContainer.style.display='flex';
                getCategories();
                getProperties();
                getOpticalDrives();
            } else loginErrorText.innerText=data.error;
        });
  });
  opticalDriveLink.addEventListener("click", (event) => {
    event.preventDefault();
    getOpticalDrives();
  });
categoryLink.addEventListener("click", (event) => {
    event.preventDefault();
    getCategories();
  });
propertyLink.addEventListener("click", (event) => {
    event.preventDefault();
    getProperties();
  });
logoutLink.addEventListener("click", (event) => {
    event.preventDefault();
    fetch('http://localhost/lab/app/api/loginController.php?action=logout')
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        loginContainer.style.display='flex';
        contentContainer.style.display='none';
        loginErrorText.innerText='';
    });
  });
document.body.addEventListener('click', function (e) { 
    if(e.target.className === 'delete-category') {
        e.preventDefault();
        fetch("http://localhost/lab/app/api/categoryController.php?id="+e.target.getAttribute('data-id'),
        {
            method: "DELETE"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            getCategories();
        });
    } else if(e.target.className === 'delete-property') {
        e.preventDefault();
        fetch("http://localhost/lab/app/api/propertyController.php?id="+e.target.getAttribute('data-id'),
        {
            method: "DELETE"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            getProperties();
        });
    } else if(e.target.className === 'delete-opticalDrive') {
        e.preventDefault();
        fetch("http://localhost/lab/app/api/opticalDriveController.php?id="+e.target.getAttribute('data-id'),
        {
            method: "DELETE"
        }).then((response) => {
            return response.json();
        }).then(()=>{
            getOpticalDrives();
        });
    } else if(e.target.className === 'edit-category'){
        e.preventDefault();
        fetch('http://localhost/lab/app/api/categoryController.php?id='+e.target.getAttribute('data-id'))
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            document.getElementById('categoryNameInput').value=data.name;
            document.getElementById('categoryIdInput').value=data.id;
        });
    } else if(e.target.className === 'edit-property'){
        e.preventDefault();
        fetch('http://localhost/lab/app/api/propertyController.php?id='+e.target.getAttribute('data-id'))
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            document.getElementById('propertyUnitsInput').value=data.units;
            document.getElementById('propertyNameInput').value=data.name;
            document.getElementById('propertyIdInput').value=data.id;
        });
    } else if(e.target.className === 'edit-opticalDrive'){
        e.preventDefault();
        fetch('http://localhost/lab/app/api/opticalDriveController.php?id='+e.target.getAttribute('data-id'))
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            document.getElementById('opticalDriveNameInput').value=data.name;
            document.getElementById('opticalDriveVendorInput').value=data.vendor;
            document.getElementById('opticalDrivePriceInput').value=data.price;
            document.getElementById('opticalDriveCategoryInput').value=data.category_id;
            document.getElementById('opticalDriveIdInput').value=data.id;
            for (i=0;i<data.properties.length;i++){
                document.querySelectorAll(".prop-input[data-id='"+data.properties[i].property_id+"']")[0].value=data.properties[i].value;
            }
            document.querySelectorAll(".prop-input[data-id='2']")[0];
        });
    }
}, false);