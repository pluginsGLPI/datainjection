import glpiEslintConfig from '../../eslint.config.mjs';

export default [
    ...glpiEslintConfig,
    {
        ignores: [
            'node_modules/*',
            'vendor/*',
        ],
    }
];
