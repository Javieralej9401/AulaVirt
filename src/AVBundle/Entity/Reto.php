<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reto
 *
 * @ORM\Table(name="Reto")
 * @ORM\Entity
 */
class Reto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
   
    /**
     * @var string
     *
     *@ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

       /**
     * @var Grupo
     *
     * @ORM\ManyToOne(targetEntity="Grupo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idgrupo", referencedColumnName="id")
     * })
     */
    private $idgrupo;
    

    /**
     * @var string
     *
     *@ORM\Column(name="status", type="string", length=10, nullable=false)
     */
    private $status="No contestado";


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCreacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaContestado", type="datetime", nullable=true)
     */
    private $fechaContestado;

    /**
     * @var integer
     *
     * @ORM\Column(name="puntos", type="integer", nullable=true)
     */
    private $puntos;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="retosApersonas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="retador", referencedColumnName="id")
     * })
     */
    private $retador;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToMany(targetEntity="Usuario", cascade={"persist","remove"}, mappedBy="retosDePersonas")
     */
    private $retados;

     /**
     * @var \RetoPregunta
     *
     * @ORM\OneToMany(targetEntity="RetoPregunta", cascade={"persist","remove"}, mappedBy="idReto")
     */
    private $preguntas;

    public function __toString(){
        return $this->nombre;
    }
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
     * Constructor
     */
    public function __construct()
    {
        $this->retados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Reto
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Reto
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Reto
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaContestado
     *
     * @param \DateTime $fechaContestado
     * @return Reto
     */
    public function setFechaContestado($fechaContestado)
    {
        $this->fechaContestado = $fechaContestado;

        return $this;
    }

    /**
     * Get fechaContestado
     *
     * @return \DateTime 
     */
    public function getFechaContestado()
    {
        return $this->fechaContestado;
    }

    /**
     * Set puntos
     *
     * @param integer $puntos
     * @return Reto
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;

        return $this;
    }

    /**
     * Get puntos
     *
     * @return integer 
     */
    public function getPuntos()
    {
        return $this->puntos;
    }

    /**
     * Set retador
     *
     * @param \AVBundle\Entity\Usuario $retador
     * @return Reto
     */
    public function setRetador(\AVBundle\Entity\Usuario $retador = null)
    {
        $this->retador = $retador;

        return $this;
    }

    /**
     * Get retador
     *
     * @return \AVBundle\Entity\Usuario 
     */
    public function getRetador()
    {
        return $this->retador;
    }

    /**
     * Add retados
     *
     * @param \AVBundle\Entity\Usuario $retados
     * @return Reto
     */
    public function addRetado(\AVBundle\Entity\Usuario $retados)
    {
        $this->retados[] = $retados;

        return $this;
    }

    /**
     * Remove retados
     *
     * @param \AVBundle\Entity\Usuario $retados
     */
    public function removeRetado(\AVBundle\Entity\Usuario $retados)
    {
        $this->retados->removeElement($retados);
    }

    /**
     * Get retados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRetados()
    {
        return $this->retados;
    }
 
    /**
     * Add preguntas
     *
     * @param \AVBundle\Entity\RetoPregunta $preguntas
     * @return Reto
     */
    public function addPregunta(\AVBundle\Entity\RetoPregunta $preguntas)
    {
        $this->preguntas[] = $preguntas;

        
        return $this;
    }

    /**
     * Remove preguntas
     *
     * @param \AVBundle\Entity\RetoPregunta $preguntas
     */
    public function removePregunta(\AVBundle\Entity\RetoPregunta $preguntas)
    {
        $this->preguntas->removeElement($preguntas);
    }

    /**
     * Get preguntas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }

    public function setPreguntas($preguntas){
        $this->preguntas=$preguntas;

        foreach ($preguntas as $pregunta) {
            $pregunta->setIdReto($this);
        }
        return  $this->preguntas;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Reto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

  

 
    /**
     * Set retados
     *
     * @param \AVBundle\Entity\Grupoestudiante $retados
     * @return Reto
     */
    public function setRetados(\AVBundle\Entity\Grupoestudiante $retados = null)
    {
        $this->retados = $retados;

        return $this;
    }

    /**
     * Set idgrupo
     *
     * @param \AVBundle\Entity\Grupo $idgrupo
     * @return Reto
     */
    public function setIdgrupo(\AVBundle\Entity\Grupo $idgrupo = null)
    {
        $this->idgrupo = $idgrupo;

        return $this;
    }

    /**
     * Get idgrupo
     *
     * @return \AVBundle\Entity\Grupo 
     */
    public function getIdgrupo()
    {
        return $this->idgrupo;
    }
}
