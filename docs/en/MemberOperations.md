#Member Operations

##Adding a new Member to the database

You can simply create new Member with:

```
$php supersake security:createmember some@email.com
```

If you add it like this, the password will be the same as the given email address.
The FirstName will be set with whatever you set before the @ sign.

###Options

--password / -p : Optional Password. Defaults to given emailaddress.
--firstname / -f : Optional FirstName. Default to name@ part of the emailaddress.
--surname / -s : Optional Surname.
--lastname / -l : Alias for Surname.

##Sending a reset password email

You can send a password reset email with:

```
$php supersake security:resetpassword email-or-id
```

This will send an email with the reset link.
