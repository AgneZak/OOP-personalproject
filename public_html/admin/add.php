<?php
require '../../bootloader.php';

if (!is_logged_in()) {
    header("Location: /login.php");
    exit();
}

$nav = nav();

$form = [
    'attr' => [
        'method' => 'POST'
    ],
    'fields' => [
        'x' => [
            'label' => 'X coordinates',
            'type' => 'number',
            'validators' => [
                'validate_field_not_empty',
                'validate_field_range' => [
                    'min' => 0,
                    'max' => 490
                ]
            ],
            'extra' => [
                'attr' => [
                    'placeholder' => 'X coordinates',
                    'class' => 'input-field'
                ]
            ]
        ],
        'y' => [
            'label' => 'Y coordinates',
            'type' => 'number',
            'validators' => [
                'validate_field_not_empty',
                'validate_field_range' => [
                    'min' => 0,
                    'max' => 490
                ]
            ],
            'extra' => [
                'attr' => [
                    'placeholder' => 'Y coordinates',
                    'class' => 'input-field'
                ]
            ]
        ],
        'color' => [
            'label' => 'Pick a color',
            'type' => 'select',
            'options' => [
                'black' => 'Black',
                'red' => 'Red',
                'green' => 'Green',
                'blue' => 'Blue'
            ],
            'validators' => [
                'validate_select',
                'validate_field_not_empty'
            ],
            'value' => 'red'
        ],
    ],
    'buttons' => [
        'submit' => [
            'title' => 'Prideti',
            'type' => 'submit',
            'extra' => [
                'attr' => [
                    'class' => 'btn'
                ]
            ]
        ],
        'clear' => [
            'title' => 'Clear',
            'type' => 'reset',
            'extra' => [
                'attr' => [
                    'class' => 'btn'
                ]
            ]
        ]
    ],
    'validators' => [
        'validate_field_coordinates' => [
            'x',
            'y'
        ],
    ]
];

$clean_inputs = get_clean_input($form);

if ($clean_inputs) {
    $success = validate_form($form, $clean_inputs);

    if ($success) {
        $fileDB = new FileDB(DB_FILE);

        $fileDB->load();
        $fileDB->insertRow('pixels', $clean_inputs + ['email' => $_SESSION['email']]);
        $fileDB->save();

        $p = 'Sveikinu ivedus pixeli';
    } else {
        $p = 'Uzpildyki visus laukus';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/media/style.css">
    <title>Add</title>
</head>
<body>
<main>
    <header>

        <?php require ROOT . '/app/templates/nav.tpl.php'; ?>

    </header>
    <section class="wrapper">
        <h1 class="header header--main">Prideki pixel'i</h1>

        <?php require ROOT . '/core/templates/form.tpl.php'; ?>

        <?php if (isset ($p)): ?>
            <p><?php print $p; ?></p>
        <?php endif; ?>

    </section>
</main>
</body>
</html>

