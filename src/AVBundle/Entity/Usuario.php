<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\Criteria;
/**
 * Usuario
 *
 * @ORM\Table(name="Usuario", indexes={@ORM\Index(name="idTipousuario", columns={"idTipousuario"}), @ORM\Index(name="iddepartamento", columns={"iddepartamento"})})
 * @ORM\Entity
 */
class Usuario implements UserInterface, \Serializable
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
     * @ORM\Column(name="login", type="string", length=50, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="clave", type="string", length=50, nullable=false)
     */
    private $clave;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=200, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula", type="string", length=15, nullable=false)
     */
    private $cedula;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="tlf", type="string", length=20, nullable=false)
     */
    private $tlf;

    /**
     * @var \Tipousuario
     *
     * @ORM\ManyToOne(targetEntity="Tipousuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTipousuario", referencedColumnName="id")
     * })
     */
    private $idtipousuario;

    /**
     * @var \Departamento
     *
     * @ORM\ManyToOne(targetEntity="Departamento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddepartamento", referencedColumnName="id")
     * })
     */
    private $iddepartamento;

    /**
     * Grupos donde esta inscrito un estudiante 
     * @var \Grupoestudiante
     *
     * @ORM\OneToMany(targetEntity="Grupoestudiante", cascade={"persist","remove"}, mappedBy="idEstudiante")
     */
    private $GruposInscrito;
    /**
     * @var \Grupo
     *
     * @ORM\OneToMany(targetEntity="Grupo", cascade={"persist","remove"}, mappedBy="idprofesor")
     */
    private $grupos;

     /**
     * @var \FotoPerfil
     *
     *@ORM\OneToOne(targetEntity="FotoPerfil", cascade={"persist","remove"}, mappedBy="idusuario")
     */
    private $idFotoPerfil;


     /**
     * @var \Reto
     *
     * @ORM\OneToMany(targetEntity="Reto", cascade={"persist","remove"}, mappedBy="retador")
     */
    private $retosApersonas;

      /**
     * @var \Reto
     *
     * @ORM\ManyToMany(targetEntity="Reto", inversedBy="retados" )
     * @ORM\JoinTable(name="UsuarioReto")
     */
    private $retosDePersonas;
    
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
     * Set login
     *
     * @param string $login
     * @return Usuario
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set clave
     *
     * @param string $clave
     * @return Usuario
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave
     *
     * @return string 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
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
     * Set apellido
     *
     * @param string $apellido
     * @return Usuario
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     * @return Usuario
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tlf
     *
     * @param string $tlf
     * @return Usuario
     */
    public function setTlf($tlf)
    {
        $this->tlf = $tlf;

        return $this;
    }

    /**
     * Get tlf
     *
     * @return string 
     */
    public function getTlf()
    {
        return $this->tlf;
    }

    /**
     * Set idtipousuario
     *
     * @param \AVBundle\Entity\Tipousuario $idtipousuario
     * @return Usuario
     */
    public function setIdtipousuario(\AVBundle\Entity\Tipousuario $idtipousuario = null)
    {
        $this->idtipousuario = $idtipousuario;

        return $this;
    }

    /**
     * Get idtipousuario
     *
     * @return \AVBundle\Entity\Tipousuario 
     */
    public function getIdtipousuario()
    {
        return $this->idtipousuario;
    }

    /**
     * Set iddepartamento
     *
     * @param \AVBundle\Entity\Departamento $iddepartamento
     * @return Usuario
     */
    public function setIddepartamento(\AVBundle\Entity\Departamento $iddepartamento = null)
    {
        $this->iddepartamento = $iddepartamento;

        return $this;
    }

    /**
     * Get iddepartamento
     *
     * @return \AVBundle\Entity\Departamento 
     */
    public function getIddepartamento()
    {
        return $this->iddepartamento;
    }
     /*
    
    Metodos Obligatorios para \Serializable y UserInterface 


    */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
    public function getRoles()
    {
        return array('ROLE_'.$this->getIdtipousuario()->getRole());
    }

    public function eraseCredentials()
    {
    }
    public function getUsername()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->clave;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->login,
            $this->clave,
            $this->nombre,
            $this->grupos,
            $this->retosApersonas,
            $this->retosDePersonas,
            $this->idFotoPerfil,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->clave,
            $this->nombre,
            $this->grupos,
            $this->retosApersonas,
            $this->retosDePersonas,
            $this->idFotoPerfil,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add grupos
     *
     * @param \AVBundle\Entity\Grupo $grupos
     * @return Usuario
     */
    public function addGrupo(\AVBundle\Entity\Grupo $grupos)
    {
        $this->grupos[] = $grupos;

        return $this;
    }

    /**
     * Remove grupos
     *
     * @param \AVBundle\Entity\Grupo $grupos
     */
    public function removeGrupo(\AVBundle\Entity\Grupo $grupos)
    {
        $this->grupos->removeElement($grupos);
    }

    /**
     * Get grupos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    /**
     * Add GruposInscrito
     *
     * @param \AVBundle\Entity\Grupoestudiante $gruposInscrito
     * @return Usuario
     */
    public function addGruposInscrito(\AVBundle\Entity\Grupoestudiante $gruposInscrito)
    {
        $this->GruposInscrito[] = $gruposInscrito;

        return $this;
    }

    /**
     * Remove GruposInscrito
     *
     * @param \AVBundle\Entity\Grupoestudiante $gruposInscrito
     */
    public function removeGruposInscrito(\AVBundle\Entity\Grupoestudiante $gruposInscrito)
    {
        $this->GruposInscrito->removeElement($gruposInscrito);
    }

    /**
     * Get GruposInscrito
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGruposInscrito()
    {
        return $this->GruposInscrito;
    }

    /**
     * Set idFotoPerfil
     *
     * @param \AVBundle\Entity\FotoPerfil $idFotoPerfil
     * @return Usuario
     */
    public function setIdFotoPerfil(\AVBundle\Entity\FotoPerfil $idFotoPerfil = null)
    {
        $this->idFotoPerfil = $idFotoPerfil;

        return $this;
    }

    /**
     * Get idFotoPerfil
     *
     * @return \AVBundle\Entity\FotoPerfil 
     */
    public function getIdFotoPerfil()
    {
        return $this->idFotoPerfil;
    }

    public function verificarGrupoInscrito($idGrupo){ 
       
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('idGrupo', $idGrupo));
        return sizeof( $this->GruposInscrito->matching($criteria) );
    }

    /**
     * Add retosApersonas
     *
     * @param \AVBundle\Entity\Reto $retosApersonas
     * @return Usuario
     */
    public function addRetosApersona(\AVBundle\Entity\Reto $retosApersonas)
    {
        $this->retosApersonas[] = $retosApersonas;

        return $this;
    }

    /**
     * Remove retosApersonas
     *
     * @param \AVBundle\Entity\Reto $retosApersonas
     */
    public function removeRetosApersona(\AVBundle\Entity\Reto $retosApersonas)
    {
        $this->retosApersonas->removeElement($retosApersonas);
    }

    /**
     * Get retosApersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRetosApersonas()
    {
        return $this->retosApersonas;
    }



    /**
     * Add retosDePersonas
     *
     * @param \AVBundle\Entity\Reto $retosDePersonas
     * @return Usuario
     */
    public function addRetosDePersona(\AVBundle\Entity\Reto $retosDePersonas)
    {
        $this->retosDePersonas[] = $retosDePersonas;

        return $this;
    }

    /**
     * Remove retosDePersonas
     *
     * @param \AVBundle\Entity\Reto $retosDePersonas
     */
    public function removeRetosDePersona(\AVBundle\Entity\Reto $retosDePersonas)
    {
        $this->retosDePersonas->removeElement($retosDePersonas);
    }

    /**
     * Get retosDePersonas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRetosDePersonas()
    {
        return $this->retosDePersonas;
    }
}
