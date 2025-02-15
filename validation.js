function validateForm() {
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    if (email === '' || password === '') {
        alert('All fields are required');
        return false;
    }

    if (!email.includes('@')) {
        alert('Enter a valid email address');
        return false;
    }
    return true;
}
