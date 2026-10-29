import React, { useState } from 'react';
import { useAuth } from './context/AuthContext';
import { LoginView } from './components/LoginView';
import { Header } from './components/Header';
import { Sidebar } from './components/Sidebar';
import { DashboardStaff } from './components/DashboardStaff';
import { DashboardKades } from './components/DashboardKades';
import { WargaList } from './components/WargaList';
import { WargaForm } from './components/WargaForm';
import { WargaDetail } from './components/WargaDetail';
import { SuratList } from './components/SuratList';
import { SuratForm } from './components/SuratForm';
import { SuratDetail } from './components/SuratDetail';
import { SuratPrintPreview } from './components/SuratPrintPreview';
import { ActivityLogView } from './components/ActivityLogView';

export function App() {
  const { user } = useAuth();
  const [currentView, setCurrentView] = useState('dashboard');
  const [sidebarOpenMobile, setSidebarOpenMobile] = useState(false);
  const [sidebarCollapsedDesktop, setSidebarCollapsedDesktop] = useState(false);

  const handleToggleSidebar = () => {
    if (typeof window !== 'undefined' && window.innerWidth >= 1024) {
      setSidebarCollapsedDesktop(prev => !prev);
    } else {
      setSidebarOpenMobile(prev => !prev);
    }
  };

  if (!user) {
    return <LoginView />;
  }

  const renderContent = () => {
    if (currentView === 'dashboard') {
      return user.role === 'kades' ? (
        <DashboardKades onNavigate={setCurrentView} />
      ) : (
        <DashboardStaff onNavigate={setCurrentView} />
      );
    }

    if (currentView === 'warga') {
      return <WargaList onNavigate={setCurrentView} />;
    }

    if (currentView === 'warga_create') {
      return <WargaForm onNavigate={setCurrentView} />;
    }

    if (currentView.startsWith('warga_edit_')) {
      const id = Number(currentView.replace('warga_edit_', ''));
      return <WargaForm wargaId={id} onNavigate={setCurrentView} />;
    }

    if (currentView.startsWith('warga_show_')) {
      const id = Number(currentView.replace('warga_show_', ''));
      return <WargaDetail wargaId={id} onNavigate={setCurrentView} />;
    }

    if (currentView === 'surat') {
      return <SuratList mode="all" onNavigate={setCurrentView} />;
    }

    if (currentView === 'surat_drafts') {
      return <SuratList mode="drafts" onNavigate={setCurrentView} />;
    }

    if (currentView === 'validasi') {
      return <SuratList mode="validasi" onNavigate={setCurrentView} />;
    }

    if (currentView === 'arsip') {
      return <SuratList mode="arsip" onNavigate={setCurrentView} />;
    }

    if (currentView === 'surat_create') {
      return <SuratForm onNavigate={setCurrentView} />;
    }

    if (currentView.startsWith('surat_create_jenis_')) {
      const jenisId = Number(currentView.replace('surat_create_jenis_', ''));
      return <SuratForm initialJenisId={jenisId} onNavigate={setCurrentView} />;
    }

    if (currentView.startsWith('surat_edit_')) {
      const id = Number(currentView.replace('surat_edit_', ''));
      return <SuratForm suratId={id} onNavigate={setCurrentView} />;
    }

    if (currentView.startsWith('surat_create_for_')) {
      const wargaId = Number(currentView.replace('surat_create_for_', ''));
      return <SuratForm initialWargaId={wargaId} onNavigate={setCurrentView} />;
    }

    if (currentView.startsWith('surat_detail_')) {
      const id = Number(currentView.replace('surat_detail_', ''));
      return <SuratDetail suratId={id} onNavigate={setCurrentView} />;
    }

    if (currentView.startsWith('surat_print_')) {
      const id = Number(currentView.replace('surat_print_', ''));
      return <SuratPrintPreview suratId={id} onNavigate={setCurrentView} />;
    }

    if (currentView === 'logs') {
      return <ActivityLogView />;
    }

    return <DashboardStaff onNavigate={setCurrentView} />;
  };

  return (
    <div className="flex min-h-screen bg-[#f8fafc] font-sans text-slate-900">
      <Sidebar
        currentView={currentView}
        onNavigate={(view) => {
          setCurrentView(view);
          setSidebarOpenMobile(false);
        }}
        isOpenMobile={sidebarOpenMobile}
        onCloseMobile={() => setSidebarOpenMobile(false)}
        isCollapsedDesktop={sidebarCollapsedDesktop}
        onToggleDesktop={() => setSidebarCollapsedDesktop(prev => !prev)}
      />

      <div className="flex flex-1 flex-col min-w-0 transition-all duration-200">
        <Header
          onToggleSidebar={handleToggleSidebar}
          isSidebarCollapsed={sidebarCollapsedDesktop}
        />
        <main className="flex-1 p-3 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
          {renderContent()}
        </main>
      </div>
    </div>
  );
}

export default App;
