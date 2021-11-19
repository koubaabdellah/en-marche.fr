<?php

namespace App\Entity\Pap;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\EntityIdentityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="pap_building")
 *
 * @ApiResource(
 *     subresourceOperations={
 *         "building_blocks_get_subresource": {
 *             "method": "GET",
 *             "path": "/v3/pap/buildings/{id}/building_blocks",
 *             "requirements": {"id": "%pattern_uuid%"},
 *         },
 *     },
 * )
 */
class Building implements CampaignStatisticsOwnerInterface
{
    use EntityIdentityTrait;
    use CampaignStatisticsTrait;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Pap\Address", inversedBy="building")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Address $address = null;

    /**
     * @var BuildingBlock[]|Collection
     *
     * @ApiSubresource
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Pap\BuildingBlock",
     *     mappedBy="building",
     *     cascade={"all"},
     *     orphanRemoval=true,
     *     fetch="EAGER"
     * )
     * @ORM\OrderBy({"name": "ASC"})
     *
     * @Groups({"pap_address_list"})
     */
    private Collection $buildingBlocks;

    /**
     * @var BuildingStatistics[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Pap\BuildingStatistics",
     *     mappedBy="building",
     *     cascade={"all"},
     *     orphanRemoval=true
     * )
     */
    private Collection $statistics;

    public function __construct(UuidInterface $uuid = null)
    {
        $this->uuid = $uuid ?? Uuid::uuid4();
        $this->buildingBlocks = new ArrayCollection();
        $this->statistics = new ArrayCollection();
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return BuildingBlock[]|Collection
     */
    public function getBuildingBlocks(): Collection
    {
        return $this->buildingBlocks;
    }

    public function addBuildingBlock(BuildingBlock $buildingBlock): void
    {
        if (!$this->buildingBlocks->contains($buildingBlock)) {
            $buildingBlock->setBuilding($this);
            $this->buildingBlocks->add($buildingBlock);
        }
    }

    public function removeBuildingBlock(BuildingBlock $buildingBlock): void
    {
        $this->buildingBlocks->removeElement($buildingBlock);
    }
}
