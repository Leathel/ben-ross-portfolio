type CodeTokenKind =
    | 'comment'
    | 'string'
    | 'number'
    | 'keyword'
    | 'builtin'
    | 'identifier';

const SQL_KEYWORDS = new Set([
    'ADD',
    'ALL',
    'AND',
    'AS',
    'ASC',
    'BY',
    'CASCADE',
    'CREATE',
    'DELETE',
    'DESC',
    'DISTINCT',
    'DROP',
    'FROM',
    'GROUP',
    'HAVING',
    'IN',
    'INDEX',
    'INNER',
    'INSERT',
    'INTO',
    'IS',
    'JOIN',
    'KEY',
    'LIKE',
    'LIMIT',
    'NOT',
    'NULL',
    'ON',
    'OR',
    'ORDER',
    'PRIMARY',
    'REFERENCES',
    'SELECT',
    'SET',
    'TABLE',
    'UNIQUE',
    'UPDATE',
    'VALUES',
    'WHERE',
]);

const CODE_BUILTINS = new Set([
    'COUNT',
    'COALESCE',
    'CONCAT',
    'DATE',
    'FK',
    'INDEXES',
    'UNIX_TIMESTAMP',
    'VARCHAR',
]);

const TOKEN_PATTERN =
    /(--[^\n]*|\/\*[\s\S]*?\*\/)|('(?:\\.|''|[^'])*'|"(?:\\.|""|[^"])*"|`(?:\\.|``|[^`])*`)|(\b\d+(?:\.\d+)?\b)|([A-Za-z_][A-Za-z0-9_]*)/g;

function tokenKindForWord(word: string): CodeTokenKind | null {
    const upper = word.toUpperCase();

    if (SQL_KEYWORDS.has(upper)) {
        return 'keyword';
    }

    if (CODE_BUILTINS.has(upper)) {
        return 'builtin';
    }

    if (word.includes('_')) {
        return 'identifier';
    }

    return null;
}

/**
 * Add lightweight syntax colours to rich-text code blocks without changing
 * the sanitized HTML stored in the database.
 */
export function highlightCodeBlocks(root: ParentNode): void {
    root.querySelectorAll<HTMLElement>('pre > code').forEach((code) => {
        if (code.dataset.highlighted === 'true') {
            return;
        }

        const source = code.textContent ?? '';

        if (source.trim() === '') {
            return;
        }

        const fragment = document.createDocumentFragment();
        let lastIndex = 0;
        let match: RegExpExecArray | null;

        TOKEN_PATTERN.lastIndex = 0;

        while ((match = TOKEN_PATTERN.exec(source)) !== null) {
            const [value, comment, string, numeric, word] = match;

            if (match.index > lastIndex) {
                fragment.append(
                    document.createTextNode(
                        source.slice(lastIndex, match.index),
                    ),
                );
            }

            const kind: CodeTokenKind | null = comment
                ? 'comment'
                : string
                  ? 'string'
                  : numeric
                    ? 'number'
                    : word
                      ? tokenKindForWord(word)
                      : null;

            if (kind) {
                const token = document.createElement('span');
                token.className = `code-token code-token--${kind}`;
                token.textContent = value;
                fragment.append(token);
            } else {
                fragment.append(document.createTextNode(value));
            }

            lastIndex = match.index + value.length;
        }

        if (lastIndex < source.length) {
            fragment.append(document.createTextNode(source.slice(lastIndex)));
        }

        code.replaceChildren(fragment);
        code.dataset.highlighted = 'true';
    });
}
