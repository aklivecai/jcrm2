<?php
Yii :: import('zii.widgets.CMenu');

$mems = array(
	'AdminLog' => array(),
	'Manage' => array(),
	'Clientele' => array(),
	'AddressBook' => array(),
	'AddressGroups' => array(),
	'Files' => array(),
	'Events' => array(),

);
class MyMenu extends CMenu {
// must set this to allow  parameter changes in CMenu widget call
	public $isParent = true;
	public function init()
	{
		parent::init();
	}
	public function run()
	{
		$this->renderMenu($this->items);
	}
 	protected function renderMenu($items) {
		$n = count($items);
		if($n > 0)
		{
			echo CHtml::openTag('ul', $this->htmlOptions);
			$count = 0;
			foreach ($items as $item)
			{
				$count++;

				if (true||isset($item['divider']))
				{
					$options = isset($item['itemOptions']) ? $item['itemOptions'] : array();
					$classes = array();

					if ($item['active'] && $this->activeCssClass != '')
						$classes[] = $this->activeCssClass;

					if ($count === 1 && $this->firstItemCssClass !== null)
						$classes[] = $this->firstItemCssClass;

					if ($count === $n && $this->lastItemCssClass !== null)
						$classes[] = $this->lastItemCssClass;

					if ($this->itemCssClass !== null&&!empty($item['items']))
						$classes[] = $this->itemCssClass;


					if (isset($item['disabled']))
						$classes[] = 'disabled';

					if (!empty($classes))
					{
						$classes = implode(' ', $classes);
						if (!empty($options['class']))
							$options['class'] .= ' '.$classes;
						else
							$options['class'] = $classes;
					}

					echo CHtml::openTag('li', $options);

					$menu = $this->renderMenuItem($item);

					if (isset($this->itemTemplate) || isset($item['template']))
					{
						$template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
						echo strtr($template, array('{menu}' => $menu));
					}
					else
						echo $menu;

					if (isset($item['items']) && !empty($item['items']))
					{
						$this->controller->widget('application.components.MyMenu', array(
							'encodeLabel'=>$this->encodeLabel,
							'htmlOptions'=>isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions,
							'isParent' =>false,
							'items'=>$item['items'],
						));
					}

					echo '</li>';
				}
			}

			echo '</ul>';
		}
	}
	protected function renderMenuItem($item)
	{
		if (isset($item['icon']))
		{

			if (strpos($item['icon'], 'icon') === false
				&&strpos($item['icon'], 'isw-') === false
				&&strpos($item['icon'], 'isb-') === false
			)
			{
				$pieces = explode(' ', $item['icon']);
				$item['icon'] = 'icon-'.implode(' icon-', $pieces);
			}

			$item['label'] = '<i class="'.$item['icon'].'"></i> '.$item['label'];
		}

		if (!isset($item['linkOptions']))
			$item['linkOptions'] = array();

		if (isset($item['items']) && !empty($item['items']))
		{
			$item['url'] = '#';

			if (isset($item['linkOptions']['class']))
				$item['linkOptions']['class'] .= ' dropdown-toggle';
			else
				$item['linkOptions']['class'] = 'dropdown-toggle';

			// $item['linkOptions']['data-toggle'] = 'dropdown';
			$item['label'] .= '';
		}

		if (isset($item['url']))
			return CHtml::link($item['label'], $item['url'], $item['linkOptions']);
		else
			return $item['label'];
	}	

}