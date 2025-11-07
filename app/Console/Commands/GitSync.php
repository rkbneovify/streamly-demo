<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GitSync extends Command
{
    // Define the command signature and description
    protected $signature = 'git:sync
                            {message? : Commit message (required for normal use)}
                            {--auto : Run all steps automatically (no confirmation prompts)}';

    protected $description = 'Commit, stash, pull from newstaging, pop, and push with merge-conflict detection.';

    public function handle()
    {
        $message = $this->argument('message');
        $auto = $this->option('auto');

        // Detect current branch
        exec('git rev-parse --abbrev-ref HEAD', $currentBranchOutput);
        $currentBranch = $currentBranchOutput[0] ?? null;
        if (!$currentBranch) {
            $this->error("❌ Could not detect current branch.");
            return 1;
        }
        $this->info("🌿 Current branch: {$currentBranch}");

        // Remote branch to pull from
        $remoteBranch = 'newstaging';

        // Help command
        if ($message === 'help') {
            return $this->showHelp();
        }

        // If no commit message is provided, offer push-only mode
        if (empty($message)) {
            $this->info("🚀 Checking local commits on current branch: {$currentBranch}...");

            // Count local commits not yet pushed
            exec("git log origin/{$currentBranch}..HEAD --oneline", $commits);
            $count = count($commits);

            if ($count === 0) {
                $this->info("✅ No local commits to push.");
                return 0;
            }

            $this->info("⚡ {$count} local commit(s) not yet pushed:");
            foreach ($commits as $line) {
                $this->line("   • {$line}");
            }

            // Ask user whether to push
            if ($this->confirm("\n🚀 Do you want to push these commits to origin/{$currentBranch}?")) {
                $this->info("Pushing commits...");
                exec("git push origin {$currentBranch} 2>&1", $pushOutput, $pushResult);
                $this->outputLines($pushOutput);
                if ($pushResult === 0) {
                    $this->info("✅ Push complete!");
                } else {
                    $this->error("❌ Push failed!");
                }
            } else {
                $this->warn("⏩ Push skipped.");
            }

            return 0;
        }

        $this->info("🚀 Starting Git sync: commit → stash → pull → pop → push");
        if ($auto) {
            $this->warn("⚙️  Running in AUTO mode — all confirmations skipped!");
        }

        // STEP 1️⃣: Check for local changes
        $this->info("\n🧩 Checking for local changes...");
        exec('git status --porcelain', $statusOutput);
        $hasChanges = !empty($statusOutput);
        $didStash = false;

        if ($hasChanges) {
            if ($auto || $this->confirm("📦 Local changes found. Commit these changes?")) {
                $this->info("🔧 Staging and committing on {$currentBranch}...");
                exec('git add .');
                exec("git commit -m \"$message\"", $commitOutput);
                $this->outputLines($commitOutput);
            } elseif ($auto || $this->confirm("Would you like to stash your local changes instead?")) {
                $this->info("📥 Stashing local changes...");
                exec('git stash', $stashOutput);
                $this->outputLines($stashOutput);
                $didStash = true;
            } else {
                $this->warn("⏩ Skipped committing and stashing local changes.");
            }
        } else {
            $this->warn("⚠️  No local changes to commit or stash.");
        }

        // STEP 2️⃣: Pull latest changes from newstaging
        if ($auto || $this->confirm("\n🔄 Pull latest changes from origin/{$remoteBranch}?")) {
            $this->info("Fetching updates from origin/{$remoteBranch}...");
            exec("git fetch origin {$remoteBranch} 2>&1");
            exec("git pull origin {$remoteBranch} 2>&1", $pullOutput, $pullResult);
            $this->outputLines($pullOutput);

            if ($pullResult !== 0) {
                $this->error("❌ Pull failed! Aborting sync.");
                return 1;
            }

            if ($this->hasMergeConflicts()) {
                $this->error("\n🚨 Merge conflicts detected after pull! Resolve them manually.");
                return 1;
            }
        } else {
            $this->warn("⏩ Skipped pulling remote changes.");
        }

        // STEP 3️⃣: Pop stash if used
        if ($didStash) {
            if ($auto || $this->confirm("\n📤 Pop (restore) your stashed changes?")) {
                $this->info("Restoring stashed changes...");
                exec('git stash pop 2>&1', $popOutput, $popResult);
                $this->outputLines($popOutput);

                if ($this->hasMergeConflicts()) {
                    $this->error("\n🚨 Merge conflicts detected after stash pop! Resolve manually before pushing.");
                    return 1;
                }
            } else {
                $this->warn("⏩ Skipped stash pop.");
            }
        }

        // STEP 4️⃣: Push current branch to remote
        if ($auto || $this->confirm("\n🚀 Push updates to origin/{$currentBranch}?")) {
            $this->info("Pushing changes...");
            exec("git push origin {$currentBranch} 2>&1", $pushOutput, $pushResult);
            $this->outputLines($pushOutput);

            if ($pushResult === 0) {
                $this->info("✅ Push complete!");
            } else {
                $this->error("❌ Push failed!");
            }
        } else {
            $this->warn("⏩ Push skipped.");
        }

        $this->info("\n🎉 Git sync finished successfully!");
        return 0;
    }

    private function showHelp()
    {
        $this->line("\n📘  GitSync Command Help");
        $this->line(str_repeat('=', 35));
        $this->line("\n🧭  Usage:");
        $this->info("  php artisan git:sync \"commit message\" [--auto]");
        $this->line("\n⚙️  Description:");
        $this->line("  Commits local changes on current branch, pulls latest code from newstaging, restores stashed work, and pushes current branch.");
        $this->line("\n🔹 Options:");
        $this->line("  message    Commit message (required for normal workflow)");
        $this->line("  --auto     Run automatically without Y/N prompts");
        $this->line("\n💡  Notes:");
        $this->line("  • If no commit message is provided, the command will check un-pushed commits on current branch and offer to push only.\n");
        return 0;
    }

    private function outputLines(array $lines)
    {
        foreach ($lines as $line) {
            $this->line($line);
        }
    }

    private function hasMergeConflicts(): bool
    {
        exec('git diff --name-only --diff-filter=U', $conflictFiles);
        if (!empty($conflictFiles)) {
            $this->line("\n⚠️  Conflicted files:");
            foreach ($conflictFiles as $file) {
                $this->line("   - " . $file);
            }
            return true;
        }
        return false;
    }
}
