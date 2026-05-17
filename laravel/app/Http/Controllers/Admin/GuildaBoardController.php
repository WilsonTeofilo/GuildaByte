<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardCard;
use App\Models\BoardColumn;
use App\Models\CardActivityLog;
use App\Models\CardComment;
use App\Models\GuildaBoard;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuildaBoardController extends Controller
{
    // ── Board View ──────────────────────────────────────────────

    public function show(Project $project)
    {
        $board = GuildaBoard::firstOrCreate(
            ['project_id' => $project->id],
            ['name' => "Board - {$project->name}"]
        );

        // Se for board novo, cria colunas padrão
        if ($board->wasRecentlyCreated || $board->columns()->count() === 0) {
            $defaultColumns = ['Backlog', 'Design', 'Desenvolvimento', 'Testes', 'Concluído'];
            foreach ($defaultColumns as $index => $colTitle) {
                BoardColumn::create([
                    'guilda_board_id' => $board->id,
                    'title' => $colTitle,
                    'position' => $index,
                ]);
            }
        }

        $board->load(['columns' => function($query) {
            $query->orderBy('position');
        }, 'columns.cards' => function($query) {
            $query->orderBy('position');
        }, 'columns.cards.comments.user']);

        return view('admin.board.show', compact('project', 'board'));
    }

    // ── Colunas CRUD ────────────────────────────────────────────

    public function storeColumn(Request $request, GuildaBoard $board)
    {
        $request->validate(['title' => 'required|string|max:50']);
        
        $position = $board->columns()->max('position') + 1;
        
        BoardColumn::create([
            'guilda_board_id' => $board->id,
            'title' => $request->title,
            'position' => $position,
        ]);

        return back()->with('success', 'Coluna criada.');
    }

    // ── Cards CRUD & Move ────────────────────────────────────────

    public function storeCard(Request $request, BoardColumn $column)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'type'  => 'required|in:task,bug,feature',
        ]);

        $position = $column->cards()->max('position') + 1;

        $card = BoardCard::create([
            'board_column_id' => $column->id,
            'title'           => $request->title,
            'type'            => $request->type,
            'position'        => $position,
        ]);

        CardActivityLog::create([
            'board_card_id' => $card->id,
            'user_id'       => auth()->id(),
            'action'        => 'created',
            'details'       => 'Card criado na coluna '.$column->title,
        ]);

        return back();
    }

    public function moveCard(Request $request, BoardCard $card)
    {
        $request->validate([
            'target_column_id' => 'required|exists:board_columns,id',
            'new_position'     => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $card) {
            $oldCol = clone $card->column;
            $newColId = $request->target_column_id;
            
            // Reordenação na nova coluna (e remoção virtual da antiga via position update)
            // Aqui uma lógica simplificada, em prod exige reordenamento de lista
            
            $card->update([
                'board_column_id' => $newColId,
                'position'        => $request->new_position,
            ]);

            if ($oldCol->id != $newColId) {
                $newCol = BoardColumn::find($newColId);
                CardActivityLog::create([
                    'board_card_id' => $card->id,
                    'user_id'       => auth()->id(),
                    'action'        => 'moved_column',
                    'details'       => "Movido de {$oldCol->title} para {$newCol->title}",
                ]);
                
                // Trigger que atualiza a timeline do cliente dependendo da coluna "Concluído"
                // se todos os cards essenciais estiverem em Concluído, avança o status do projeto.
            }
        });

        if ($request->wantsJson()) return response()->json(['success' => true]);
        return back();
    }

    // ── Comentários ──────────────────────────────────────────────

    public function storeComment(Request $request, BoardCard $card)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        CardComment::create([
            'board_card_id' => $card->id,
            'user_id'       => auth()->id(),
            'content'       => $request->content,
        ]);

        return back();
    }
}
