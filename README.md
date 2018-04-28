# CSHP
A CSS preprocessor written in PHP and interpreted on the server-side, with features inspired by SASS and some Emmet snippets

## What you can do with CSHP?

### Nest elements
PHP:
```
$cshp->rule("ul", [ 
  "list-style-type" => "none",
  "li" => [
    "border-bottom": "1px solid #CCC"
  ]
]);
```
CSS:
```
ul {
  list-style-type: none,
}
  ul li {
    border-bottom: 1px solid #CCC;
  }
```

### Nest properties
PHP:
```
$cshp->rule("body", [ 
  "color" => "#333",
  "%font" => [
    "family" => "Helvetica, Arial, sans-serif",
    "size" => "16px"
  ]
]);
```
CSS:
```
body {
  color: #333;
  font-family: Helvetica, Arial, sans-serif;
  font-size: 16px;
}
```

### Nest pseudo-classes
PHP:
```
$cshp->rule("a", [ 
  "td:n",
  "&hover" => [ "color" => "green" ]
]);
```
CSS:
```
a {
  text-decoration: none;
}
  a:hover {
    color: green;
  }
```

### Emmet snippets
PHP:
```
$cshp->rule("a", [
  "td:n", 
  "fw:b, 
  "ff:a"
]);
```
CSS:
```
a {
  text-decoration: none;
  font-weight: bold;
  font-family: Arial, Helvetica, sans-serif;
}
```

### Mixins
PHP:
```
$cshp->mixin("bdRadius", ["radius"], [
    "border-radius" => "{{radius}}"
  ]
);
$cshp->rule(".box", [
  "width" => "300px",
  "@bdRadius" => ["15px"]
]);
```
CSS:
```
.box {
  width: 300px;
  border-radius: 15px;
}
```

### Extends
PHP:
```
$cshp->mixin("center", [
    "margin" => "0 auto"
  ]
);
$cshp->rule(".mainContent", [
  "width" => "960px",
  "@center"
]);
```
CSS:
```
.mainContent {
  width: 960px;
  margin: 0 auto;
}
```

And of course use the PHP features in your advantage (database integration maybe?)

-----

### The compilation

You can compile your CSS code in two ways:

#### Direct in the PHP script
```
$cshp->compile();
```
And call the PHP file in the link tag:
```
<link rel="stylesheet" href="style/main.php">
```
This way you can use more dynamical data in the back-end.

#### Or compile a CSS file
```
$cshp->compile("folder", "fileName.css");
```
Providing a folder and a file name (optional) the output CSS will be in a common CSS file

#### Minify
When you instance the CSHP object you can include the "compress" option, so the output CSS will be compressed:
```
$cshp = new Cshp(["compress, snippets"]);
```
