define({ "api": [
  {
    "type": "post",
    "url": "/forgot-password",
    "title": "Notify a user has forgotten password",
    "name": "ForgotPassword",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User's email.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/login",
    "title": "Login with push notification parameter",
    "name": "Login",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User Email.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pswd",
            "description": "<p>User Password.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "type",
            "description": "<p>Device type for push notification. 1 : iOS, 2 : Android.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "dtoken",
            "description": "<p>Device token for push notification.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/register",
    "title": "Register a user",
    "name": "Register",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User Email.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pswd",
            "description": "<p>User Password.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/reset-password",
    "title": "Reset Password",
    "name": "ResetPassword",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User's email.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Verification code.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "newpass",
            "description": "<p>New Password.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/get-data",
    "title": "Get date-specific data",
    "name": "GetData",
    "group": "Data",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "year",
            "description": "<p>Year of date.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "month",
            "description": "<p>Month of date.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "day",
            "description": "<p>Day of date.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Data"
  },
  {
    "type": "post",
    "url": "/set-data",
    "title": "Write date-specific data",
    "name": "SetData",
    "group": "Data",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "year",
            "description": "<p>Year of date.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "month",
            "description": "<p>Month of date.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "day",
            "description": "<p>Day of date.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "data",
            "description": "<p>Data to write.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Data"
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./apidoc/main.js",
    "group": "E__xampp_htdocs_tooth_apidoc_main_js",
    "groupTitle": "E__xampp_htdocs_tooth_apidoc_main_js",
    "name": ""
  }
] });
