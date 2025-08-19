const getStatusColor = (status: string) => {
    switch (status) {
        case 'Em andamento':
            return 'border border-green-600 bg-green-500/20 text-green-500';
        case 'concluído':
            return 'border border-red-600 bg-red-500/20 text-red-500';
        case 'Cancelado':
            return 'border border-pink-600 bg-pink-500/20 text-pink-500';
        case 'Pendente':
            return 'border border-orange-600 bg-orange-500/20 text-orange-500';
        case 'Em revisão':
            return 'border border-purple-600 bg-purple-500/20 text-purple-500';
        case 'Pausado':
            return 'border border-amber-600 bg-amber-500/20 text-amber-500';
        case 'Arquivado':
            return 'border border-slate-600 bg-slate-500/20 text-slate-500';
        default:
            return 'border border-gray-600 bg-gray-500/20 text-gray-500';
    }
}

export default getStatusColor