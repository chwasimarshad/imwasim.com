<?php
/** Seed the local preview with production-ready blog settings and the first article. */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run this file through wp eval-file.\n");
    exit(1);
}

$title = 'Model Context Protocol (MCP): An Introduction for Engineering Leaders';
$slug = 'model-context-protocol-mcp-introduction';
$excerpt = 'A practical introduction to MCP: what it standardizes, how hosts, clients, and servers work together, and how to adopt it responsibly in healthcare software.';

$content = <<<'HTML'
<p>AI systems become useful when they can do more than generate text. They need relevant context, trusted data, and a controlled way to take action. Model Context Protocol, usually called MCP, provides a shared language for those connections.</p>

<p>For engineering leaders, MCP is less about a new transport and more about reducing integration friction. A team can expose a capability once, place policy around it, and make it available to compatible AI applications without rebuilding a custom connector for every model or user experience.</p>

<h2 id="why-mcp-exists">Why MCP exists</h2>

<p>Most useful AI workflows cross system boundaries. A care operations assistant may need to read an approved policy, check a scheduling system, summarize a case, and open a follow-up task. Historically, each connection required proprietary glue: a custom function signature, authentication flow, schema, error model, and prompt convention.</p>

<p>That approach works for a prototype. It becomes expensive when the number of models, applications, and business systems grows. It also makes governance inconsistent because every integration invents its own boundary.</p>

<p>MCP standardizes how an AI application discovers and uses external capabilities. The underlying API, database, or service still does the real work. MCP adds a consistent protocol-facing layer so the AI host can understand what is available, what inputs are required, and what result came back.</p>

<blockquote>MCP is a contract between an AI application and the systems around it. The value comes from making capabilities discoverable, typed, and governable.</blockquote>

<h2 id="mental-model">A useful mental model: host, client, server</h2>

<p>Three roles help make the architecture concrete:</p>

<ul>
<li><strong>The host</strong> is the AI application the person interacts with. It owns the conversation, chooses what context to assemble, and should remain responsible for consent and policy.</li>
<li><strong>The client</strong> is the protocol component inside the host. It manages the connection and exchanges MCP messages with a server.</li>
<li><strong>The server</strong> exposes a focused set of capabilities backed by real services or data. A server might wrap a knowledge base, a ticketing system, or a clinical workflow API.</li>
</ul>

[mcp_architecture]

<p>This separation supports clear ownership. The server does not need to know how the final chat or agent experience is designed. The host does not need a one-off integration for every underlying system. Both sides depend on the protocol contract.</p>

<h2 id="primitives">Tools, resources, and prompts</h2>

<p>MCP servers expose three core primitives. They may sound similar because all three can inform an AI workflow, but each assigns control differently.</p>

[mcp_primitives]

<p><strong>Tools</strong> perform actions or computations. A tool can accept typed parameters, call a backend, and return a structured result. <strong>Resources</strong> provide addressable context such as documents, schemas, or records. <strong>Prompts</strong> are reusable interaction templates that a person can select to start a known workflow.</p>

<p>The control distinction matters. It helps product and security teams reason about whether the user, the application, or the model decides when a capability participates. That decision should be explicit for every sensitive workflow.</p>

<h2 id="request-flow">How an MCP request flows</h2>

<p>A robust implementation is a sequence of bounded decisions, not an invisible jump from a prompt to a production system.</p>

[mcp_flow]

<ol>
<li><strong>Discover:</strong> the client learns which capabilities the server currently exposes.</li>
<li><strong>Understand:</strong> the host reads the capability description and input schema so the model can form a valid request.</li>
<li><strong>Authorize:</strong> identity, scopes, organizational policy, and user consent are evaluated before sensitive work.</li>
<li><strong>Execute:</strong> the server validates inputs and invokes the underlying API or service.</li>
<li><strong>Observe:</strong> the system records the outcome, exposes useful errors, and creates an audit trail appropriate to the risk.</li>
</ol>

<p>Good descriptions are part of the security model. A vague tool such as <code>update_record</code> gives the model and user too little information. A narrow tool with precise inputs, consequences, and constraints is easier to approve, test, monitor, and revoke.</p>

<h2 id="healthcare">A healthcare example</h2>

<p>Imagine an assistant that helps a care coordinator prepare a patient follow-up. The host could connect to separate MCP servers for approved clinical guidance, scheduling availability, and care-management tasks.</p>

<p>The assistant first reads a policy resource. It then proposes a scheduling lookup with the minimum necessary parameters. After the coordinator confirms the action, the host calls the scheduling tool. Finally, the assistant drafts a follow-up task while the coordinator remains the decision-maker.</p>

<p>MCP makes those connections consistent; it does not make them compliant by itself. Healthcare teams still need strong identity, least-privilege access, tenant isolation, data minimization, encryption, audit logs, retention controls, business associate agreements where applicable, and a clear human-oversight model.</p>

<h3>Design the boundary around the job</h3>

<p>A server should expose the smallest coherent capability needed for the workflow. Do not mirror an entire internal API merely because it already exists. Start from the user job, publish narrow operations, and keep business validation in the system that owns the data.</p>

<h3>Keep deterministic controls outside the model</h3>

<p>The model can help interpret intent, choose among permitted tools, and summarize results. Authentication, authorization, validation, transaction limits, and audit logging belong in deterministic application and server code.</p>

<h2 id="adoption">An adoption checklist for engineering leaders</h2>

<ul>
<li><strong>Choose one measurable workflow.</strong> Select a repeated task with clear users, data owners, and a result you can evaluate.</li>
<li><strong>Inventory existing APIs.</strong> MCP usually wraps capabilities you already trust; it should not duplicate core business logic.</li>
<li><strong>Define control explicitly.</strong> Decide which context the application supplies, which actions the model may propose, and where the person must approve.</li>
<li><strong>Threat-model the whole path.</strong> Include prompt injection, confused-deputy risks, excessive scopes, data leakage, replay, and unsafe downstream side effects.</li>
<li><strong>Make tools narrow and typed.</strong> Use descriptive names, strict schemas, bounded outputs, and actionable error responses.</li>
<li><strong>Evaluate behavior, not just connectivity.</strong> Test tool selection, argument quality, refusal behavior, latency, failure recovery, and user comprehension.</li>
<li><strong>Instrument from day one.</strong> Track calls, approvals, errors, latency, and business outcomes without logging more sensitive data than necessary.</li>
</ul>

<h2>What MCP does not solve</h2>

<p>MCP does not choose the right product experience, fix a weak authorization model, guarantee trustworthy data, or eliminate the need for evaluation. It is an interoperability layer. Architecture quality still depends on the boundaries around it.</p>

<p>It also does not replace your existing APIs. An MCP server often adapts those APIs into capabilities suited to AI applications. The underlying service remains the source of truth and should continue to enforce business rules.</p>

<h2>Where to begin</h2>

<p>Build a thin vertical slice. Connect one host to one narrowly scoped server, keep a person in control of consequential actions, and measure whether the workflow becomes faster or more reliable. The first goal is not a universal agent platform. It is evidence that a carefully governed capability improves real work.</p>

<p>Once that boundary is dependable, reuse becomes the multiplier. Other compatible hosts can discover the same capability, and the server team can improve its contract without reimplementing every client integration.</p>

<div class="article-sources">
<h2>Official resources</h2>
<ul>
<li><a href="https://modelcontextprotocol.io/docs/getting-started/intro" rel="noopener">Model Context Protocol documentation</a></li>
<li><a href="https://modelcontextprotocol.io/specification/draft/server/index" rel="noopener">MCP server concepts and primitives</a></li>
<li><a href="https://registry.modelcontextprotocol.io/docs" rel="noopener">Official MCP Registry documentation</a></li>
<li><a href="https://blog.modelcontextprotocol.io/" rel="noopener">MCP project blog</a></li>
</ul>
</div>

<h2>Frequently asked questions</h2>

<h3>What is Model Context Protocol?</h3>
<p>MCP is an open standard for connecting AI applications to external tools, data, and reusable prompts through a consistent interface.</p>

<h3>Does MCP replace APIs?</h3>
<p>No. MCP commonly sits above existing APIs and data systems, giving AI applications a standard way to discover and use their capabilities.</p>

<h3>Is MCP safe for healthcare data?</h3>
<p>MCP can be part of a secure healthcare architecture, but compliance depends on the complete implementation: authentication, authorization, consent, data minimization, auditing, vendor controls, and the systems beneath it.</p>
HTML;

$existing = get_page_by_path($slug, OBJECT, 'post');
$post_data = [
    'ID' => $existing ? $existing->ID : 0,
    'post_title' => $title,
    'post_name' => $slug,
    'post_excerpt' => $excerpt,
    'post_content' => $content,
    'post_status' => 'publish',
    'post_type' => 'post',
    'post_author' => 1,
];
$post_id = wp_insert_post(wp_slash($post_data), true);
if (is_wp_error($post_id)) {
    fwrite(STDERR, $post_id->get_error_message() . "\n");
    exit(1);
}

$category = term_exists('AI Architecture', 'category');
if (!$category) {
    $category = wp_insert_term('AI Architecture', 'category', ['slug' => 'ai-architecture']);
}
if (!is_wp_error($category)) {
    wp_set_post_categories($post_id, [(int) $category['term_id']]);
}
wp_set_post_tags($post_id, ['Model Context Protocol', 'AI Agents', 'Software Architecture', 'Healthcare AI']);

update_post_meta($post_id, 'rank_math_title', 'Model Context Protocol (MCP): Introduction | Wasim Arshad');
update_post_meta($post_id, 'rank_math_description', $excerpt);
update_post_meta($post_id, 'rank_math_focus_keyword', 'Model Context Protocol');

update_option('blogname', 'Wasim Arshad | Architecture, AI & Engineering Leadership');
update_option('blogdescription', 'Practical writing about AI-powered healthcare systems, software architecture, and engineering leadership.');
update_option('timezone_string', 'Asia/Karachi');
update_option('date_format', 'F j, Y');
update_option('posts_per_page', 9);
update_option('permalink_structure', '/%postname%/');
update_option('rank_math_is_configured', true);

foreach (get_posts(['post_type' => ['post', 'page'], 'post_status' => 'any', 'numberposts' => -1]) as $item) {
    if ((int) $item->ID !== (int) $post_id && in_array($item->post_name, ['hello-world', 'sample-page'], true)) {
        wp_delete_post($item->ID, true);
    }
}

flush_rewrite_rules();
echo "Seeded article #{$post_id}: {$title}\n";
