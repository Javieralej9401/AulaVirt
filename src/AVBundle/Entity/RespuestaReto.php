<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RespuestaReto
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RespuestaReto
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
     * @var Reto
     *
     * @ORM\ManyToOne(targetEntity="Reto")
     * @ORM\JoinColumn(name="idreto", referencedColumnName="id")
     */
    private $idReto;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     */


    private $idUsuario;


    /**
     * @var string
     *
     * @ORM\Column(name="respuestaRetado", type="text")
     */
    private $respuestaRetado;


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
     * Set respuestaRetado
     *
     * @param string $respuestaRetado
     * @return RespuestaReto
     */
    public function setRespuestaRetado($respuestaRetado)
    {
        $this->respuestaRetado = $respuestaRetado;

        return $this;
    }

    /**
     * Get respuestaRetado
     *
     * @return string 
     */
    public function getRespuestaRetado()
    {
        return $this->respuestaRetado;
    }

    /**
     * Set idReto
     *
     * @param \AVBundle\Entity\Reto $idReto
     * @return RespuestaReto
     */
    public function setIdReto(\AVBundle\Entity\Reto $idReto = null)
    {
        $this->idReto = $idReto;

        return $this;
    }

    /**
     * Get idReto
     *
     * @return \AVBundle\Entity\Reto 
     */
    public function getIdReto()
    {
        return $this->idReto;
    }

    /**
     * Set idUsuario
     *
     * @param \AVBundle\Entity\Usuario $idUsuario
     * @return RespuestaReto
     */
    public function setIdUsuario(\AVBundle\Entity\Usuario $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \AVBundle\Entity\Usuario 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
}
