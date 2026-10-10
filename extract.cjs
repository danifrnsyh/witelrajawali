const fs = require('fs');
const lines = fs.readFileSync('C:/Users/itzda/.gemini/antigravity/brain/c1641904-7074-4eb5-998b-d08c2c8aa4da/.system_generated/logs/transcript_full.jsonl', 'utf8').split('\n');

let targetContent = null;

for (let line of lines) {
    if (!line.trim()) continue;
    try {
        const json = JSON.parse(line);
        if (json.tool_calls) {
            for (let call of json.tool_calls) {
                if (call.function.name === 'default_api:replace_file_content' || call.function.name === 'default_api:write_to_file') {
                    if (call.function.arguments.TargetFile && call.function.arguments.TargetFile.includes('kinerja-teknisi.blade.php')) {
                        if (call.function.arguments.ReplacementContent) {
                            // If it's a partial replacement, we'd need to apply it... Wait, no, write_to_file is what I need.
                        } else if (call.function.arguments.CodeContent) {
                            targetContent = call.function.arguments.CodeContent;
                        }
                    }
                }
            }
        }
    } catch(e) {}
}

if (targetContent) {
    fs.writeFileSync('resources/views/livewire/kinerja-teknisi.blade.php', targetContent);
    console.log("Restored successfully!");
} else {
    console.log("Not found.");
}
