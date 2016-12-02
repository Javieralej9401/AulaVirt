<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioReto
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UsuarioReto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

      /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuarioId;

    /**
     * @var Reto
     *
     * @ORM\ManyToOne(targetEntity="Reto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reto_id", referencedColumnName="id")
     * })
     */
    private $retoId;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set usuarioId
     *
     * @param \AVBundle\Entity\Usuario $usuarioId
     * @return UsuarioReto
     */
    public function setUsuarioId(\AVBundle\Entity\Usuario $usuarioId = null)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * Get usuarioId
     *
     * @return \AVBundle\Entity\Usuario 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Set retoId
     *
     * @param \AVBundle\Entity\Reto $retoId
     * @return UsuarioReto
     */
    public function setRetoId(\AVBundle\Entity\Reto $retoId = null)
    {
        $this->retoId = $retoId;

        return $this;
    }

    /**
     * Get retoId
     *
     * @return \AVBundle\Entity\Reto 
     */
    public function getRetoId()
    {
        return $this->retoId;
    }
}
