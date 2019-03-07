<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo,$page, PaginationService $pagination)
    {
        //$rep = $this->getDoctrine()->getRepository(Comment::class);
        //$comments = $repo->findAll();
        $pagination->setEntityClass(Comment::class)
                                ->setLimit(5)
                              //  ->setRoute('admin_comment_index')
                                ->setPage($page);

        return $this->render('admin/comments/index.html.twig', [
            'paginator' => $pagination,
        ]);
    }
  

      /**
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     */
    public function edit(Comment $comment,Request $request,ObjectManager $manager){
       
        $form = $this->createForm(AdminCommentType::class,$comment);
        $form->handleRequest($request);

        if($form->IsSubmitted() && $form->IsValid() ){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success',"Le commentaire {$comment->getId()} a été bien modifié !");
        }

        return $this->render('admin/comments/edit.html.twig',[
            'form' => $form->createView(),
            'comment'   => $comment
        ]);
    }
/**
 * Permet de supprimer un commentaire
 * @Route("/admin/comment/{id}/delete",name="admin_comment_delete")
 *
 * @param Comment $comment
 * @param ObjectManager $manager
 * @return void
 */
    public function delete(Comment $comment,ObjectManager $manager){
     $manager->remove($comment);
     $manager->flush();
     $this->addFlash('success',"Le commentaire a bien été supprimé! ");
     return $this->redirectToRoute('admin_comment_index');
    }

    
}
