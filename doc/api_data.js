define({ "api": [
  {
    "type": "post",
    "url": "/auth/forgot-password",
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
    "url": "/auth/login",
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
            "field": "push_type",
            "description": "<p>Device type for push notification. 1 : iOS, 2 : Android.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "push_token",
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
    "url": "/auth/login",
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
            "field": "full_name",
            "description": "<p>User's full name.</p>"
          },
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
            "field": "push_type",
            "description": "<p>Device type for push notification. 1 : iOS, 2 : Android.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "push_token",
            "description": "<p>Device token for push notification.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "google_id",
            "description": "<p>Google ID</p>"
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
    "url": "/auth/login",
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
            "field": "full_name",
            "description": "<p>User's full name.</p>"
          },
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
            "field": "push_type",
            "description": "<p>Device type for push notification. 1 : iOS, 2 : Android.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "push_token",
            "description": "<p>Device token for push notification.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "facebook_id",
            "description": "<p>Facebook Id</p>"
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
    "url": "/auth/register",
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
            "field": "full_name",
            "description": "<p>User Full name.</p>"
          },
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
            "field": "push_type",
            "description": "<p>Device type for push notification. 1 : iOS, 2 : Android.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "push_token",
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
    "url": "/auth/reset-password",
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
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "push_type",
            "description": "<p>Device type for push notification. 1 : iOS, 2 : Android.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "push_token",
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
    "group": "E__xampp_htdocs_homer_apidoc_main_js",
    "groupTitle": "E__xampp_htdocs_homer_apidoc_main_js",
    "name": ""
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
    "filename": "./doc/main.js",
    "group": "E__xampp_htdocs_homer_doc_main_js",
    "groupTitle": "E__xampp_htdocs_homer_doc_main_js",
    "name": ""
  },
  {
    "type": "post",
    "url": "/post/add",
    "title": "Add Post",
    "name": "AddPost",
    "group": "Post",
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
            "type": "String",
            "optional": false,
            "field": "post_type",
            "description": "<p>1 : &quot;need&quot; or 2 : &quot;has&quot;</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "property_type",
            "description": "<p>either of &quot;apartment&quot;, &quot;house&quot;, &quot;penthouse&quot;, etc...</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "location",
            "description": "<p>Google location string</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "num_rooms",
            "description": "<p>Number of rooms</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "area",
            "description": "<p>area</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "price",
            "description": "<p>Price</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Description</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Post"
  },
  {
    "type": "post",
    "url": "/post/delete",
    "title": "Delete Post",
    "name": "DeletePost",
    "group": "Post",
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
            "field": "post_id",
            "description": "<p>Post ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Post"
  },
  {
    "type": "post",
    "url": "/post/edit",
    "title": "Edit Post",
    "name": "EditPost",
    "group": "Post",
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
            "field": "post_id",
            "description": "<p>Post ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "post_type",
            "description": "<p>1 : &quot;need&quot; or 2 : &quot;has&quot;</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "property_type",
            "description": "<p>either of &quot;apartment&quot;, &quot;house&quot;, &quot;penthouse&quot;, etc...</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "location",
            "description": "<p>Google location string</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "num_rooms",
            "description": "<p>Number of rooms</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "area",
            "description": "<p>area</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "price",
            "description": "<p>Price</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Description</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Post"
  },
  {
    "type": "post",
    "url": "/post/get-all",
    "title": "Get Own Post",
    "name": "GetAllPosts",
    "group": "Post",
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
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Post"
  },
  {
    "type": "post",
    "url": "/post/get-own-detail",
    "title": "Get Own Post",
    "name": "GetOwnPostDetail",
    "group": "Post",
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
            "field": "post_id",
            "description": "<p>Post ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Post"
  },
  {
    "type": "post",
    "url": "/post/get-own",
    "title": "Get Own Post",
    "name": "GetOwnPosts",
    "group": "Post",
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
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Post"
  },
  {
    "type": "post",
    "url": "/post/get-detail",
    "title": "Get Post",
    "name": "GetPostDetail",
    "group": "Post",
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
            "field": "post_id",
            "description": "<p>Post ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "Post"
  },
  {
    "type": "post",
    "url": "/user/get-mine",
    "title": "Get My Profile",
    "name": "GetMyProfile",
    "group": "User",
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
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "/user/get",
    "title": "Get User Profile",
    "name": "GetUserProfile",
    "group": "User",
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
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "/user/rate",
    "title": "Rate User",
    "name": "RateUser",
    "group": "User",
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
            "field": "post_id",
            "description": "<p>Post ID</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./api.php",
    "groupTitle": "User"
  }
] });