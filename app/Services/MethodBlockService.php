<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Connection;
use App\Models\Method;
use App\Models\MethodBlock;
use App\Models\Port;

class MethodBlockService
{
    public function save(Block $source, array $data): MethodBlock
    {
        $block = Block::findOrFail($data['block_id']);
        return $source->methodBlocks()->create(
            $data + [
                'constant' => $block->default_constant,
            ]
        );
    }

    /**
     * @throws \Exception
     */
    public function connect(MethodBlock $source, MethodBlock $target, Port $sourcePort, Port $targetPort): Connection
    {
        $outgoingDirection = $sourcePort->direction;
        $incomingDirection = $targetPort->direction;

        if ($source->block->type === 'start') {
            $outgoingDirection = !$outgoingDirection;
        }

        if ($target->block->type === 'end') {
            $incomingDirection = !$incomingDirection;
        }

        // Check if source is output and target is input
        if (!$outgoingDirection || $incomingDirection) {
            throw new \Exception('Invalid port connection');
        }

        $sourceType = $sourcePort->type;
        $targetType = $targetPort->type;

        // remove generics from type
        $sourceType = explode('<', $sourceType)[0];
        $targetType = explode('<', $targetType)[0];

        // Check if the port types match or if the target port is any
        if ($sourceType !== $targetType && $targetType !== 'any' && $sourceType !== 'any') {
            throw new \Exception('Invalid port type, expected ' . $targetPort->type . ' got ' . $sourcePort->type . '.');
        }

        if ($source->connectionsOut()->where('from_port_id', '=', $targetPort->id)->count() > 0) {
            throw new \Exception('Source port already connected');
        }

        if ($target->connectionsIn()->where('to_port_id', '=', $sourcePort->id)->count() > 0) {
            throw new \Exception('Target port already connected');
        }

        return Connection::create([
            'block_id' => $source->parent_id,
            'from_method_block_id' => $source->id,
            'to_method_block_id' => $target->id,
            'from_port_id' => $sourcePort->id,
            'to_port_id' => $targetPort->id,
        ]);
    }
}
