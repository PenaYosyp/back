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
function checkLogin() {
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
            getopticalDrives();
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
        for(let i = 0; i < data.length; i++) {
            selectContent+=`<option value="`+data[i].name+`">`+data[i].name+`</option>`
            content+=`<tr>
                        <td>` + data[i].id + `</td>
                        <td>` + data[i].name + `</td>
            </tr>`;
        }
        dataTable.innerHTML=`<thead>
                                <th>ID</th>
                                <th>Назва</th>
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
        for(let i = 0; i < data.length; i++) {
            content+=`<tr>
                        <td>` + data[i].id + `</td>
                        <td>` + data[i].name + `</td>
                        <td>` + data[i].units + `</td>
            </tr>`;
        }
        dataTable.innerHTML=`<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Одиниці вимірювання</th>
                            </thead>
                            <tbody>
                                `+content+`
                            </tbody>`;
    });
}
function getopticalDrives() {
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
        for(let i = 0; i < data.length; i++) {
            let propertyContent=``;
            for(const [key, value] of Object.entries(data[i].properties))
                propertyContent+=key+`: `+value+`</br>`;
            content+=`<tr>
                        <td>` + data[i].id + `</td>
                        <td>` + data[i].name + `</td>
                        <td>` + data[i].vendor + `</td>
                        <td>` + data[i].category + `</td>
                        <td>` + data[i].price + `</td>
                        <td>` + propertyContent + `</td>
            </tr>`;
        }
        dataTable.innerHTML=`<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Виробник</th>
                                <th>Категорія</th>
                                <th>Ціна</th>
                                <th>Характеристики</th>
                            </thead>
                            <tbody>
                                ` + content + `
                            </tbody>`;
    });
}
checkLogin();

categoryForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let categoryName=document.getElementById('categoryNameInput').value;
    let formData = new FormData();
    formData.append('name', categoryName);
    fetch("http://localhost/lab/app/api/categoryController.php",
        {
            body: formData,
            method: "POST"
        }).then(()=>{
            categoryForm.reset();
            getCategories();
        });
  });
propertyForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let propertyName=document.getElementById('propertyNameInput').value;
    let propertyUnits=document.getElementById('propertyUnitsInput').value;
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
  });
opticalDriveForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let opticalDriveName=document.getElementById('opticalDriveNameInput').value;
    let opticalDriveVendor=document.getElementById('opticalDriveVendorInput').value;
    let opticalDriveCategory=document.getElementById('opticalDriveCategoryInput').value;
    let opticalDrivePrice=document.getElementById('opticalDrivePriceInput').value;
    let opticalDriveProperties=document.getElementById('opticalDrivePropertiesInput').value;
    let formData = new FormData();
    formData.append('name', opticalDriveName);
    formData.append('vendor', opticalDriveVendor);
    formData.append('category', opticalDriveCategory);
    formData.append('price', opticalDrivePrice);
    formData.append('properties', opticalDriveProperties);
    fetch("http://localhost/lab/app/api/opticalDriveController.php",
        {
            body: formData,
            method: "POST"
        }).then(()=>{
            opticalDriveForm.reset();
            getopticalDrives();
        });
  });
loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    let login=document.getElementById('loginInput').value;
    let password=document.getElementById('passwordInput').value;
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
            if(data.error=='') {
                loginContainer.style.display='none';
                contentContainer.style.display='flex';
                getCategories();
                getProperties();
                getopticalDrives();
            } else {
                loginErrorText.innerText=data.error;
            }
        });
  });
  opticalDriveLink.addEventListener("click", (event) => {
    event.preventDefault();
    getopticalDrives();
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
        loginContainer.style.display = 'flex';
        contentContainer.style.display = 'none';
        loginErrorText.innerText = '';
    });
  });