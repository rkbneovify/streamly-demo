<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GitSync extends Command
{
    protected $signature = 'git:sync
                            {branch? : Branch name (optional, default is current branch)}
                            {message? : Commit message (required for normal use)}
                            {--auto : Run all steps automatically (no confirmation prompts)}';

    protected $description = 'Commit, stash, pull, pop, and push with merge-conflict detection and optional --auto flag.';

    public function handle()
    {
        $branch = $this->argument('branch');
        $message = $this->argument('message');
        $auto = $this->option('auto');

        // Smart detection: if only one argument is provided and looks like a message
        if ($branch && !$message && str_contains($branch, ':')) {
            $message = $branch;
            $branch = null; // use current branch
        }

        // Help command
        if ($branch === 'help' || $message === 'help') {
            return $this->showHelp();
        }

        // STEP 0️⃣: Determine current branch if none provided
        if (!$branch) {
            exec('git rev-parse --abbrev-ref HEAD', $currentBranchOutput);
            $branch = $currentBranchOutput[0] ?? 'unknown';
            $this->info("🌿 Using current branch: {$branch}");
        }

        // If no commit message is provided, do push-only behavior
        if (empty($message)) {
            $this->info("🚀 Checking local commits on branch: {$branch}...");

            // Ensure branch exists
            exec("git rev-parse --verify {$branch} 2>/dev/null", $output, $branchExists);
            if ($branchExists !== 0) {
                $this->warn("🌱 Local branch '{$branch}' does not exist. Aborting push check.");
                return 1;
            }

            // Count local commits not yet pushed
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

            // Ask user whether to push
            if ($this->confirm("\n🚀 Do you want to push these commits to origin/{$branch}?")) {
                $this->info("Pushing commits...");
                exec("git push origin {$branch} 2>&1", $pushOutput, $pushResult);
                foreach ($pushOutput as $line) {
                    $this->line($line);
                }
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

        // If commit message exists, proceed with normal commit/stash/pull/pop/push workflow
        $this->info("🚀 Starting Git sync on branch: {$branch}");
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
                $this->info("🔧 Staging and committing...");
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

        // STEP 2️⃣: Pull latest remote changes
        if ($auto || $this->confirm("\n🔄 Pull latest changes from origin/{$branch}?")) {
            $this->info("Fetching updates...");
            exec("git fetch origin {$branch} 2>&1");
            exec("git pull origin {$branch} 2>&1", $pullOutput, $pullResult);
            $this->outputLines($pullOutput);

            if ($pullResult !== 0) {
                $this->error("❌ Pull failed! Aborting sync.");
                return 1;
            }

            if ($this->hasMergeConflicts()) {
                $this->error("\n🚨 Merge conflicts detected after pull! Please resolve them manually before continuing.");
                return 1;
            }
        } else {
            $this->warn("⏩ Skipped pulling remote changes.");
        }

        // STEP 3️⃣: Pop stash
        if ($didStash) {
            if ($auto || $this->confirm("\n📤 Pop (restore) your stashed changes?")) {
                $this->info("Restoring stashed changes...");
                exec('git stash pop 2>&1', $popOutput, $popResult);
                $this->outputLines($popOutput);

                if ($this->hasMergeConflicts()) {
                    $this->error("\n🚨 Merge conflicts detected after stash pop! Please resolve them manually before pushing.");
                    return 1;
                }
            } else {
                $this->warn("⏩ Skipped stash pop.");
            }
        }

        // STEP 4️⃣: Push to remote
        if ($auto || $this->confirm("\n🚀 Push updates to origin/{$branch}?")) {
            $this->info("Pushing changes...");
            exec("git push origin {$branch} 2>&1", $pushOutput, $pushResult);
            $this->outputLines($pushOutput);

            if ($pushResult === 0) {
                $this->info("\n✅ Push complete!");
            } else {
                $this->error("\n❌ Push failed!");
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
        $this->info("  php artisan git:sync [branch] \"commit message\" [--auto]");
        $this->line("\n⚙️  Description:");
        $this->line("  Commits local changes (or stashes), pulls latest code, restores stashed work, and pushes to remote.");
        $this->line("\n🔹 Options:");
        $this->line("  branch     Branch to sync with (default is current branch)");
        $this->line("  message    Commit message (required for normal workflow)");
        $this->line("  --auto     Run automatically without Y/N prompts");
        $this->line("\n💡  Notes:");
        $this->line("  • If no commit message is provided, the command will check local commits and offer to push only.\n");
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
