<?php
/** Seed the local preview with production-ready blog settings and the first article. */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run this file through wp eval-file.\n");
    exit(1);
}

$title = 'Model Context Protocol (MCP): The Missing Link for AI Agents';
$slug = 'model-context-protocol-mcp-introduction';
$excerpt = 'A story-driven guide to Model Context Protocol, AI agents, tool calling, context engineering, MCP servers, and secure AI automation.';

$content = <<<'HTML'
<p>At 9:07 on Monday morning, a product team watched its new AI assistant fail a simple test. The assistant could explain the company’s support process perfectly, but it could not find the latest incident, check the customer record, or create a follow-up task. It knew how to talk about work. It could not actually do the work.</p>

<p>The problem was not the large language model. The problem was the gap between the model and the systems where useful context and actions lived. Every connection needed custom code, a new tool definition, another authentication path, and one more set of edge cases.</p>

<p>Model Context Protocol, or MCP, is designed for that gap. It is an open standard that helps AI applications connect to tools, data, and reusable workflows through a consistent interface. For teams building AI agents, agentic AI, copilots, and AI automation, MCP can become the shared integration layer that turns a fluent demo into dependable software.</p>

<h2 id="why-mcp-exists">Why MCP exists</h2>

<p>The team in our story had built four integrations for one assistant. A second AI experience would need the same four integrations again. Change one backend API and both applications could break in different ways. This many-to-many integration problem is where promising AI products often become difficult to scale.</p>

<p>Custom tool calling works for a prototype. It becomes expensive when the number of models, AI agents, applications, and business systems grows. Governance also fragments because each connector invents its own schema, permissions, errors, and prompt conventions.</p>

<p>MCP standardizes how an AI application discovers and uses external capabilities. The underlying API, database, vector store, or SaaS platform still does the real work. An MCP server adds a protocol-facing layer so an AI host can discover what is available, understand typed inputs, call the right capability, and receive a structured result.</p>

<blockquote>MCP gives AI agents a common language for reaching the tools and context around them. The breakthrough is not more intelligence. It is dependable connection.</blockquote>

<h2 id="anthropic-openai">From Anthropic to an open AI ecosystem</h2>

<p><a href="https://www.anthropic.com/news/model-context-protocol" rel="noopener">Anthropic introduced and open-sourced Model Context Protocol in November 2024</a>. The original idea was direct: replace fragmented, one-off AI integrations with an open standard for secure connections between AI assistants and the systems where data and tools live. Claude Desktop and Claude Code helped many developers encounter MCP for the first time, establishing Anthropic MCP servers as an early reference point for the ecosystem.</p>

<p>The story quickly grew beyond one company or model. In December 2025, Anthropic donated MCP to the Agentic AI Foundation under the Linux Foundation. The foundation brought together Anthropic, Block, and OpenAI alongside other major technology companies to support neutral, community-led infrastructure for agentic AI.</p>

<p><a href="https://openai.com/index/agentic-ai-foundation/" rel="noopener">OpenAI describes itself as an early MCP adopter and core contributor</a>, using the protocol as a foundation for connectors and apps in ChatGPT. For developers, the <a href="https://openai.github.io/openai-agents-python/mcp/" rel="noopener">OpenAI Agents SDK includes MCP server integration</a> for hosted remote servers, Streamable HTTP, and local stdio connections. That means an OpenAI agent can discover and call MCP tools alongside its other capabilities.</p>

<p>Anthropic and OpenAI approach agent products differently, but their support for MCP points to the same architectural idea: tools and context become more valuable when they are portable across AI applications. MCP is therefore best understood as model-neutral infrastructure. An MCP server can serve Claude, ChatGPT, an OpenAI API agent, a coding assistant, or a custom LLM application when the host supports the protocol.</p>

<h2 id="mental-model">A useful mental model: host, client, server</h2>

<p>Picture the team drawing three boxes on a whiteboard. Those boxes became the simplest useful mental model for MCP architecture:</p>

<ul>
<li><strong>The MCP host</strong> is the AI application a person uses. It owns the conversation, orchestrates the AI agent, assembles context, and remains responsible for consent and policy.</li>
<li><strong>The MCP client</strong> lives inside the host. It manages a protocol connection and exchanges structured messages with one MCP server.</li>
<li><strong>The MCP server</strong> exposes focused capabilities backed by real services or data. It might wrap a knowledge base, source-control platform, CRM, ticketing system, browser automation service, or internal API.</li>
</ul>

[mcp_architecture]

<p>The whiteboard suddenly made the architecture less mysterious. The MCP server did not need to know how the final chat, copilot, or autonomous agent experience was designed. The host no longer needed a one-off connector for every underlying system. Both sides could evolve around the same protocol contract.</p>

<h2 id="primitives">Tools, resources, and prompts</h2>

<p>Next, the team asked a practical question: what exactly should an MCP server expose? The protocol groups its core capabilities into three primitives. Each gives the AI application a different kind of power and assigns control differently.</p>

[mcp_primitives]

<p><strong>MCP tools</strong> perform actions or computations. A tool can accept typed parameters, call a backend, and return a structured result. <strong>MCP resources</strong> provide addressable context such as documents, code, schemas, or records. <strong>MCP prompts</strong> are reusable interaction templates that a person can select to begin a known workflow.</p>

<p>This distinction gave the team a better vocabulary for context engineering. Instead of pushing every possible fact into a giant prompt, the application could retrieve the right resource, offer a deliberate prompt, or let the model propose a narrowly defined tool call. The choice of primitive made control visible.</p>

<h2 id="request-flow">How an MCP request flows</h2>

<p>When the team traced its first real request, it discovered that reliable AI automation was not one magical jump from prompt to production. It was a sequence of bounded decisions.</p>

[mcp_flow]

<ol>
<li><strong>Discover:</strong> the client learns which capabilities the server currently exposes.</li>
<li><strong>Understand:</strong> the host reads the capability description and input schema so the model can form a valid request.</li>
<li><strong>Authorize:</strong> identity, scopes, organizational policy, and user consent are evaluated before sensitive work.</li>
<li><strong>Execute:</strong> the server validates inputs and invokes the underlying API or service.</li>
<li><strong>Observe:</strong> the system records the outcome, exposes useful errors, and creates an audit trail appropriate to the risk.</li>
</ol>

<p>Good tool descriptions became part of the security model. A vague operation such as <code>update_record</code> gave the LLM and the user too little information. A narrow MCP tool with precise inputs, consequences, and constraints was easier to approve, test, observe, and revoke.</p>

<h2 id="incident-story">The moment the AI agent became useful</h2>

<p>By Friday, the assistant faced the same incident question that had defeated it on Monday. This time the host connected to three focused MCP servers: one for product documentation, one for incident management, and one for task creation.</p>

<p>The AI agent first read an approved troubleshooting resource. It then used a search tool to find the active incident and compared the affected component with the customer’s configuration. Finally, it proposed a follow-up task. A person reviewed the action, approved it, and received a link to the new ticket.</p>

<p>The model had not suddenly become smarter. The system around it had become better designed. Context arrived when needed, tool calling followed typed contracts, and a human remained in control of the consequential step. That is the practical promise of MCP for AI agents.</p>

<h3>Design the boundary around the job</h3>

<p>An MCP server should expose the smallest coherent capability needed for the workflow. Do not mirror an entire internal API merely because it already exists. Start from the user’s job, publish narrow operations, and keep business validation in the system that owns the data.</p>

<h3>Keep deterministic controls outside the model</h3>

<p>The LLM can interpret intent, choose among permitted tools, and summarize results. Authentication, authorization, validation, rate limits, transaction limits, and audit logging belong in deterministic application and MCP server code.</p>

<h2 id="adoption">An adoption checklist for engineering leaders</h2>

<ul>
<li><strong>Choose one measurable AI workflow.</strong> Select a repeated task with clear users, system owners, and an outcome you can evaluate.</li>
<li><strong>Inventory existing APIs and data sources.</strong> MCP usually wraps capabilities you already trust; it should not duplicate core business logic.</li>
<li><strong>Define agent control explicitly.</strong> Decide which context the application supplies, which tool calls the model may propose, and where a person must approve.</li>
<li><strong>Threat-model the whole path.</strong> Include prompt injection, tool poisoning, confused-deputy risks, excessive scopes, data leakage, replay, and unsafe side effects.</li>
<li><strong>Make MCP tools narrow and typed.</strong> Use descriptive names, strict JSON schemas, bounded outputs, and actionable error responses.</li>
<li><strong>Evaluate agent behavior, not just connectivity.</strong> Test tool selection, argument quality, refusal behavior, latency, failure recovery, and user comprehension.</li>
<li><strong>Instrument from day one.</strong> Track MCP calls, approvals, errors, latency, token use, and business outcomes without collecting unnecessary data.</li>
</ul>

<h2>What MCP does not solve</h2>

<p>MCP does not choose the right product experience, fix a weak authorization model, guarantee trustworthy data, or eliminate AI evaluation. It is an interoperability standard. Architecture quality still depends on the boundaries around it.</p>

<p>It also does not replace APIs, retrieval-augmented generation, or an agent framework. An MCP server often adapts existing APIs into capabilities suited to AI applications. RAG can retrieve relevant knowledge. An agent framework can coordinate planning and memory. MCP gives these systems a consistent way to exchange context and invoke tools.</p>

<h2>Where to begin</h2>

<p>Start as the team in our story did: build a thin vertical slice. Connect one AI host to one narrowly scoped MCP server, keep a person in control of consequential actions, and measure whether the workflow becomes faster or more reliable. The first goal is not a universal autonomous agent platform. It is evidence that a carefully governed capability improves real work.</p>

<p>Once that boundary is dependable, reuse becomes the multiplier. Other compatible AI applications can discover the same capability, and the server team can improve its contract without rebuilding every client integration. That is how an MCP proof of concept grows into an AI platform strategy.</p>

<div class="article-sources">
<h2>Official resources</h2>
<ul>
<li><a href="https://modelcontextprotocol.io/docs/getting-started/intro" rel="noopener">Model Context Protocol documentation</a></li>
<li><a href="https://modelcontextprotocol.io/specification/draft/server/index" rel="noopener">MCP server concepts and primitives</a></li>
<li><a href="https://registry.modelcontextprotocol.io/docs" rel="noopener">Official MCP Registry documentation</a></li>
<li><a href="https://blog.modelcontextprotocol.io/" rel="noopener">MCP project blog</a></li>
<li><a href="https://www.anthropic.com/news/model-context-protocol" rel="noopener">Anthropic: Introducing the Model Context Protocol</a></li>
<li><a href="https://openai.github.io/openai-agents-python/mcp/" rel="noopener">OpenAI Agents SDK: Model Context Protocol</a></li>
<li><a href="https://openai.com/index/agentic-ai-foundation/" rel="noopener">OpenAI and the Agentic AI Foundation</a></li>
</ul>
</div>

<h2>Frequently asked questions</h2>

<h3>What is Model Context Protocol?</h3>
<p>Model Context Protocol is an open standard for connecting AI applications and AI agents to external tools, data sources, and reusable prompts through a consistent interface.</p>

<h3>Does MCP replace APIs?</h3>
<p>No. MCP commonly sits above existing APIs and data systems, giving AI applications a standard way to discover and use their capabilities.</p>

<h3>What is the difference between MCP and RAG?</h3>
<p>RAG retrieves relevant information for a model’s context. MCP is a broader interoperability protocol that can expose resources, prompts, and callable tools. A system can use both: RAG for grounded knowledge and MCP for standardized access to context and actions.</p>

<h3>Why is MCP important for agentic AI?</h3>
<p>Agentic AI needs dependable access to real systems. MCP helps an AI agent discover capabilities, understand schemas, call tools, and receive structured results without a custom integration for every host and server combination.</p>

<h3>Who created MCP, and does OpenAI support it?</h3>
<p>MCP was created and open-sourced by Anthropic in 2024. OpenAI later became an early adopter and core contributor, uses MCP for connectors and apps in ChatGPT, and supports MCP servers through the OpenAI Agents SDK.</p>
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
wp_set_post_tags($post_id, ['Model Context Protocol', 'AI Agents', 'Agentic AI', 'Tool Calling', 'Context Engineering', 'Anthropic', 'OpenAI']);

update_post_meta($post_id, 'rank_math_title', 'Model Context Protocol (MCP): AI Agents Guide | Wasim Arshad');
update_post_meta($post_id, 'rank_math_description', $excerpt);
update_post_meta($post_id, 'rank_math_focus_keyword', 'Model Context Protocol');

update_option('blogname', 'Wasim Arshad | Architecture, AI & Engineering Leadership');
update_option('blogdescription', 'Practical writing about AI agents, software architecture, intelligent automation, and engineering leadership.');
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
