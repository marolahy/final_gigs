<?php
namespace App\Datatables;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
/**
 * Class OrderDatatable
 */
class OrderDatatable extends AbstractDatatable
{

  /**
     * {@inheritdoc}
     */
     /*
    public function getLineFormatter()
    {
        $router = $this->container->get('router')->generate($route, $parameters, $referenceType);
        $formatter = function($row) {
            $row['id'] = "<a class=\"btn btn-info btn-sm\" href=\"".$router->generate('order_view',['id'=>$row['id']],UrlGeneratorInterface::ABSOLUTE_PATH)."\">View</a>";
            return $row;
        };
        return $formatter;
    }*/

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            'cdn_language_by_locale' => true,
        ));
        $this->ajax->set(array());
        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
        ));
        $this->features->set(array());
        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
            ))
            ->add('email', Column::class, array(
                'title' => 'Email',
            ))
            ->add('phone', Column::class, array(
                'title' => 'phone',
            ))
            ->add('status', Column::class, array(
                'title' => 'Status',
            ))
            ->add('amount', Column::class, array(
                'title' => 'Amount',
            ))
            ->add(null, ActionColumn::class, array(
                'title' => 'Actions',
                'start_html' => '<div class="start_actions">',
                'end_html' => '</div>',
                'actions' => array(
                  array(
                    'route' => 'order_view',
                    'route_parameters' => array(
                        'id' => 'id',
                    ),
                  'icon' => 'glyphicon glyphicon-eye-open',
                  'label' => 'View',
                  'confirm' => true,
                  'confirm_message' => 'Are you sure?',
                  'attributes' => array(
                      'rel' => 'tooltip',
                      'title' => 'Show',
                      'class' => 'btn btn-primary btn-xs',
                      'role' => 'button',
                  ),
              )
            )
          )
          );
    }
    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'App\Entity\Order';
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'order_datatable';
    }
}
