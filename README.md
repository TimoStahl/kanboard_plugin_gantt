# Kanboard Plugin Gantt

⚠ Early development version ⚠

Kanboard Plugin for a better gantt diagram with links.

Plugin for <https://github.com/kanboard/kanboard>

## Author

- [BlueTeck](https://github.com/BlueTeck)
- [Capataloutchoupika](https://github.com/Capataloutchoupika)
- based on a modified version of [frappe - gantt libary 0.5.0](https://github.com/frappe/gantt/tree/8f0b83d27d6e5f970dd4e5125120e70a3374b923)
- License MIT

## Installation

- Decompress the archive in the `plugins` folder

or

- Create a folder **plugins/Gantt**
- Copy all files under this directory

## Hooks

### Popup

You can add more rows in task popup with your custom plugin.
First, enable the Formatter hook with your custom method in your own Plugin.php :

```php
<?php

$this->hook->on("formatter:gantt:format:task", [$this, "testGantt"]);
```

Method example :

```php
<?php

/**
 * $data = array(
 *     'templateTask' => array, // contains data that will be send to popup
 *     'task' => array, // contains all data about task
 * )
 */
public function testGantt(array &$data)
{
    $data['templateTask']['assignee_name'] = $data['task']['assignee_name'];
}
```

Last thing, attach a hook to the render template :

```php
<?php

// Add a row at the top of the table
$this->template->hook->attach(
  "template:gantt:task:popup:beginning-table",
  "YouPlugin:you/template"
);
// Add a row at the bottom of the table
$this->template->hook->attach(
  "template:gantt:task:popup:and-table",
  "YouPlugin:you/template"
);
```

Template example (please, keep the `${}` format) :

```php
<tr>
    <td><?= t('Assignee') ?></td>
    <td>${task.assignee_name}</td>
</tr>
```
