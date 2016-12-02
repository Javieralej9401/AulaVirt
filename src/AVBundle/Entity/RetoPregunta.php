<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RetoPregunta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RetoPregunta
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
     * @var \Reto
     *
     * @ORM\ManyToOne(targetEntity="Reto", inversedBy="preguntas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idReto", referencedColumnName="id")
     * })
     */
    private $idReto;

    /**
     * @var string
     *
     * @ORM\Column(name="pregunta", type="text", nullable=false)
     */
    private $pregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="respuestaReal", type="text", nullable=false)
     */
    private $respuestaReal;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=20, nullable=false )
     */
    private $tipo;

    

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
     * Set idReto
     *
     * @param integer $idReto
     * @return RetoPregunta
     */
    public function setIdReto($idReto)
    {
        $this->idReto = $idReto;

        return $this;
    }

    /**
     * Get idReto
     *
     * @return integer 
     */
    public function getIdReto()
    {
        return $this->idReto;
    }

    /**
     * Set pregunta
     *
     * @param string $pregunta
     * @return RetoPregunta
     */
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return string 
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    /**
     * Set respuesta
     *
     * @param string $respuesta
     * @return RetoPregunta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return RetoPregunta
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set matClasificacion
     *
     * @param integer $matClasificacion
     * @return RetoPregunta
     */
    public function setMatClasificacion($matClasificacion)
    {
        $this->matClasificacion = $matClasificacion;

        return $this;
    }

    /**
     * Get matClasificacion
     *
     * @return integer 
     */
    public function getMatClasificacion()
    {
        return $this->matClasificacion;
    }

    /**
     * Set respuestaReal
     *
     * @param string $respuestaReal
     * @return RetoPregunta
     */
    public function setRespuestaReal($respuestaReal)
    {
        $this->respuestaReal = $respuestaReal;

        return $this;
    }

    /**
     * Get respuestaReal
     *
     * @return string 
     */
    public function getRespuestaReal()
    {
        return $this->respuestaReal;
    }

    /**
     * Set respuestaRetado
     *
     * @param string $respuestaRetado
     * @return RetoPregunta
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
}
