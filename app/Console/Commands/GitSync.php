<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GitSync extends Command
{
    protected $signature = 'git:sync
                            {message? : Commit message (optional; required for normal use)}
                            {--auto : Run all steps automatically (no confirmation prompts)}';

    protected $description = 'Commit, stash, pull from newstaging, pop, and push with local change detection.';

    public function handle()
    {
        $message = $this->argument('message');
        $auto = $this->option('auto');
        $remoteBranch = 'newstaging';

        // Detect current branch
        exec('git rev-parse --abbrev-ref HEAD', $currentBranchOutput, $branchResult);
        $currentBranch = $currentBranchOutput[0] ?? null;
        if ($branchResult !== 0 || !$currentBranch) {
            $this->error("❌ Could not detect current branch.");
            return 1;
        }
        $this->info("🌿 Current branch: {$currentBranch}");

        // Help command
        if ($message === 'help') {
            return $this->showHelp();
        }

        // Push-only mode (no message)
        if (empty($message)) {
            return $this->handlePushOnlyMode($currentBranch);
        }

        $this->info("🚀 Starting Git sync: commit → stash → pull → pop → push");
        if ($auto) {
            $this->warn("⚙️ Running in AUTO mode — all confirmations skipped!");
        }

        // STEP 1️⃣: Detect uncommitted changes (tracked or untracked)
        $this->info("\n🧩 Checking for uncommitted local changes...");
        exec('git status --porcelain', $statusOutput);
        $hasLocalChanges = !empty($statusOutput);
        $didStash = false;

        if ($hasLocalChanges) {
            $this->warn("⚠️ Local uncommitted changes found:");
            foreach ($statusOutput as $line) {
                $this->line("   • " . $line);
            }

            if ($auto || $this->confirm("\n📦 Commit these changes now?")) {
                $this->info("🔧 Staging and committing changes...");
                exec('git add -A');
                exec("git commit -m \"$message\" 2>&1", $commitOutput, $commitResult);
                $this->outputLines($commitOutput);

                if ($commitResult !== 0) {
                    $this->error("❌ Commit failed. Please check manually.");
                    return 1;
                }
                $this->info("✅ Commit complete!");
            } elseif ($auto || $this->confirm("Would you like to stash them instead?")) {
                $this->info("📥 Stashing local changes...");
                exec('git stash -u 2>&1', $stashOutput);
                $this->outputLines($stashOutput);
                $didStash = true;
            } else {
                $this->warn("⏩ Skipped committing or stashing local changes.");
            }
        } else {
            $this->info("✅ No uncommitted changes found.");
        }

        // STEP 2️⃣: Pull latest changes from remote
        if ($auto || $this->confirm("\n🔄 Pull latest changes from origin/{$remoteBranch}?")) {
            $this->info("Fetching updates from origin/{$remoteBranch}...");
            exec("git fetch origin {$remoteBranch} 2>&1");
            exec("git pull origin {$remoteBranch} 2>&1", $pullOutput, $pullResult);
            $this->outputLines($pullOutput);

            if ($pullResult !== 0) {
                $this->error("❌ Pull failed. Aborting sync.");
                return 1;
            }

            if ($this->hasMergeConflicts()) {
                $this->error("\n🚨 Merge conflicts detected after pull! Resolve them manually.");
                return 1;
            }
        } else {
            $this->warn("⏩ Pull skipped.");
        }

        // STEP 3️⃣: Pop stash if used
        if ($didStash) {
            if ($auto || $this->confirm("\n📤 Pop (restore) your stashed changes?")) {
                $this->info("Restoring stashed changes...");
                exec('git stash pop 2>&1', $popOutput, $popResult);
                $this->outputLines($popOutput);

                if ($this->hasMergeConflicts()) {
                    $this->error("\n🚨 Merge conflicts detected after stash pop! Resolve before pushing.");
                    return 1;
                }
            } else {
                $this->warn("⏩ Skipped stash pop.");
            }
        }

        // STEP 4️⃣: Push current branch
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

    /**
     * Push-only mode when no commit message is provided.
     * Now includes detection of uncommitted local changes.
     */
    private function handlePushOnlyMode(string $branch)
    {
        $this->info("🧩 Checking for local uncommitted changes...");

        exec('git status --porcelain', $statusOutput);
        $hasLocalChanges = !empty($statusOutput);

        if ($hasLocalChanges) {
            $this->warn("⚠️ Local uncommitted changes detected:");
            foreach ($statusOutput as $line) {
                $this->line("   • " . $line);
            }

            if ($this->confirm("\n📝 Do you want to commit these changes now?")) {
                $message = $this->ask("✍️ Enter a commit message");
                if (empty($message)) {
                    $this->error("❌ Commit message required. Aborting commit.");
                    return 1;
                }

                $this->info("🔧 Staging and committing changes...");
                exec('git add -A');
                exec("git commit -m \"$message\" 2>&1", $commitOutput, $commitResult);
                $this->outputLines($commitOutput);

                if ($commitResult !== 0) {
                    $this->error("❌ Commit failed. Please check manually.");
                    return 1;
                }

                $this->info("✅ Commit complete!");
            } else {
                $this->warn("⏩ Skipped committing local changes.");
            }
        } else {
            $this->info("✅ No uncommitted changes found.");
        }

        // Now check for unpushed commits
        $this->info("\n🚀 Checking local commits on {$branch}...");
        exec("git log origin/{$branch}..HEAD --oneline", $commits);
        $count = count($commits);

        if ($count === 0) {
            $this->info("✅ No local commits to push.");
            return 0;
        }

        $this->info("⚡ {$count} local commit(s) not yet pushed:");
        foreach ($commits as $line) {
            $this->line("   • {$line}");
        }

        if ($this->confirm("\n🚀 Push these commits to origin/{$branch}?")) {
            $this->info("Pushing commits...");
            exec("git push origin {$branch} 2>&1", $pushOutput, $pushResult);
            $this->outputLines($pushOutput);
            $pushResult === 0
                ? $this->info("✅ Push complete!")
                : $this->error("❌ Push failed!");
        } else {
            $this->warn("⏩ Push skipped.");
        }

        return 0;
    }

    private function showHelp()
    {
        $this->line("\n📘 GitSync Command Help");
        $this->line(str_repeat('=', 40));
        $this->line("\n🧭 Usage:");
        $this->info("  php artisan git:sync \"commit message\" [--auto]");
        $this->line("\n⚙️ Description:");
        $this->line("  Commits local changes, stashes pending work, pulls from newstaging, pops stash, and pushes branch.");
        $this->line("\n💡 Notes:");
        $this->line("  • If no commit message is given, the command checks for uncommitted and unpushed changes.");
        $this->line("  • Use --auto for CI/CD or unattended runs.\n");
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
                $this->line("   - {$file}");
            }
            return true;
        }
        return false;
    }
}
