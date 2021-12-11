var SigninPage = {};

SigninPage.init = function()
{
    $('#signin-btn-submit').click(SigninPage.signin);
    $('#signin-btn-new-user').click(SigninPage.renderSignupForm);
}

SigninPage.getForm = function()
{
    return $('#signin-form');
}

SigninPage.renderSigninForm = function()
{
    $.ajax({
      url: '/signin/form',
      dataType: 'html'
    }).always(SigninPage.afterRenderSigninForm);
}

SigninPage.afterRenderSigninForm = function(data)
{
    if (data) {
        SigninPage.getForm().html(data);
        SigninPage.init();
    }
}

SigninPage.signin = function()
{
    $.post(
        '/signin',
        SigninPage.getForm().serialize()
    ).always(SigninPage.afterSignin);
}

SigninPage.afterSignin = function(data)
{
    var errorMessage = 'Something went wrong';
    if (data && typeof data == 'object') {
        if (!data.error) {
            window.location.href = '/';
        } else {
            SigninPage.displayAlert(data.message || errorMessage, 'danger');
        }
    } else {
        SigninPage.displayAlert(errorMessage, 'warning');
    }
}

SigninPage.renderSignupForm = function()
{
    $.ajax({
      url: '/signup/form',
      dataType: 'html'
    }).always(SigninPage.afterRenderSignupForm);
}

SigninPage.afterRenderSignupForm = function(data)
{
    if (data) {
        SigninPage.getForm().html(data);
        $('#signin-btn-signup').click(SigninPage.signup);
        $('#signin-btn-back').click(SigninPage.renderSigninForm);
    }
}

SigninPage.signup = function()
{
    $.post(
        '/signup',
        SigninPage.getForm().serialize()
    ).always(SigninPage.afterSignup);
}

SigninPage.afterSignup = function(data)
{
    var errorMessage = 'Something went wrong';
    if (data && typeof data == 'object') {
        if (!data.error) {
            SigninPage.renderSignupVerificationForm();
        } else {
            SigninPage.displayAlert(data.message || errorMessage, 'danger');
        }
    } else {
        SigninPage.displayAlert(errorMessage, 'warning');
    }
}

SigninPage.renderSignupVerificationForm = function()
{
    $.ajax({
      url: '/signup-verification/form',
      dataType: 'html'
    }).always(SigninPage.afterRenderSignupVerificationForm);
}

SigninPage.afterRenderSignupVerificationForm = function(data)
{
    if (data) {
        SigninPage.getForm().html(data);
        $('#signin-btn-verify').click(SigninPage.verifySignup);
    }
}

SigninPage.verifySignup = function()
{
    $.post(
        '/signup-verification',
        SigninPage.getForm().serialize()
    ).always(SigninPage.afterSignin);
}

SigninPage.displayAlert = function(message, type)
{
    $('#signin-alert').html(message)
            .addClass('alert-'+(type || 'primary'))
            .removeClass('d-none');
}

SigninPage.init();
